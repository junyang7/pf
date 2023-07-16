<?php

namespace Junyang7\PhpCommon;

class _View
{

    public static function render($name, $data = null)
    {

        $path_v = $_ENV["_pf"]["runtime"]["wd"] . "/view/" . $name . ".html";
        $path_p = $_ENV["_pf"]["runtime"]["wd"] . "/cache/view/parsed/" . $name . ".php";
        $path_c = $_ENV["_pf"]["runtime"]["wd"] . "/cache/view/compiled/" . $name . ".html";
        self::parse($path_v, $path_p);
        $file_b = self::build($data, $path_p);
        self::cache($path_c, $file_b);
        return $file_b;

    }

    private static function parse($path_v, $path_p)
    {

        $tag_list = [
            "include" => [
                [
                    "pattern" => '/@include@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return file_get_contents(
                            sprintf(
                                '%s/%s.php',
                                $_ENV["_pf"]["runtime"]["wd"] . "/view",
                                str_ireplace(".", "/", trim($match_list[1]))
                            )
                        );
                    },
                ],
            ],
            "php" => [
                [
                    "pattern" => '/@php@\s*([^@]+)\s*@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php %s?>', $match_list[1]);
                    },
                ],
            ],
            "echo" => [
                [
                    "pattern" => '/@echo@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php echo %s?>', $match_list[1]);
                    },
                ],
            ],
            "foreach" => [
                [
                    "pattern" => '/@foreach@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php foreach (%s) { ?>', $match_list[1]);
                    },
                ],
            ],
            "endForeach" => [
                [
                    "pattern" => '/@endForeach@/',
                    "replace" => function ($match_list) {
                        return '<?php } ?>';
                    },
                ],
            ],
            "for" => [
                [
                    "pattern" => '/@for@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php for (%s) { ?>', $match_list[1]);
                    },
                ],
            ],
            "endFor" => [
                [
                    "pattern" => '/@endFor@/',
                    "replace" => function ($match_list) {
                        return '<?php } ?>';
                    },
                ],
            ],
            "while" => [
                [
                    "pattern" => '/@while@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php while (%s) { ?>', $match_list[1]);
                    },
                ],
            ],
            "endWhile" => [
                [
                    "pattern" => '/@endWhile@/',
                    "replace" => function ($match_list) {
                        return '<?php } ?>';
                    },
                ],
            ],
            "continue" => [
                [
                    "pattern" => '/@continue@/',
                    "replace" => function ($match_list) {
                        return '<?php continue ?>';
                    },
                ],
            ],
            "break" => [
                [
                    "pattern" => '/@break@/',
                    "replace" => function ($match_list) {
                        return '<?php break ?>';
                    },
                ],
            ],
            "switch" => [
                [
                    "pattern" => '/@switch@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php switch (%s) { ?>', $match_list[1]);
                    },
                ],
            ],
            "case" => [
                [
                    "pattern" => '/\s+@case@\s?([^@]+)\s?@/', // fixed bug for php cannot has any output between switch and first case
                    "replace" => function ($match_list) {
                        return sprintf('<?php case %s: ?>', $match_list[1]);
                    },
                ],
                [
                    "pattern" => '/@case@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php case %s: ?>', $match_list[1]);
                    },
                ],
            ],
            "endSwitch" => [
                [
                    "pattern" => '/@endSwitch@/',
                    "replace" => function ($match_list) {
                        return '<?php } ?>';
                    },
                ],
            ],
            "default" => [
                [
                    "pattern" => '/@default@/',
                    "replace" => function ($match_list) {
                        return '<?php default: ?>';
                    },
                ],
            ],
            "if" => [
                [
                    "pattern" => '/@if@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php if (%s) { ?>', $match_list[1]);
                    },
                ],
            ],
            "elseIf" => [
                [
                    "pattern" => '/@elseIf@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('<?php } else if (%s) { ?>', $match_list[1]);
                    },
                ],
            ],
            "else" => [
                [
                    "pattern" => '/@else@/',
                    "replace" => function ($match_list) {
                        return '<?php } else { ?>';
                    },
                ],
            ],
            "endIf" => [
                [
                    "pattern" => '/@endIf@/',
                    "replace" => function ($match_list) {
                        return '<?php } ?>';
                    },
                ],
            ],
            "json" => [
                [
                    "pattern" => '/@json@\s?([^@]+)\s?@/',
                    "replace" => function ($match_list) {
                        return sprintf('\'<?php echo json_encode(%s) ?>\'', $match_list[1]);
                    },
                ],
            ],
        ];
        $file_c = file_get_contents($path_v);
        foreach ($tag_list as $rule_list) {
            foreach ($rule_list as $rule) {
                $file_c = preg_replace_callback($rule["pattern"], $rule["replace"], $file_c);
            }
        }
        _File::write($path_p, $file_c);

    }

    private static function build($args, $path_p)
    {

        if (!is_null($args)) {
            $a = extract($args);
        }
        ob_start();
        require_once $path_p;
        $file_b = ob_get_contents();
        ob_end_clean();
        return $file_b;

    }

    private static function cache($path_c, $file_b)
    {

        _File::write($path_c, $file_b);

    }

}
