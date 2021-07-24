<?php


namespace Pf\Core;


class View
{


    public static function render($view, $args)
    {

        $app = \Pf\App::getInstance();
        $app->path_dir_view = $app->path_dir_base . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
        $app->path_dir_cache_view_parsed = $app->path_dir_base . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'parsed' . DIRECTORY_SEPARATOR;
        $app->path_dir_cache_view_compiled = $app->path_dir_base . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'compiled' . DIRECTORY_SEPARATOR;

        $path_v = sprintf('%s%s.php', $app->path_dir_view, $view);
        $path_p = sprintf('%s%s.php', $app->path_dir_cache_view_parsed, $view);
        $path_c = sprintf('%s%s.htm', $app->path_dir_cache_view_compiled, $view);

        $args['a'] = 'a';
        $args['b'] = 'b';
        $args['json'] = json_encode([
            'a' => 'A',
            'b' => 'B',
        ]);
        $args['data'] = [
            'a' => 'A',
            'b' => 'B',
        ];
        $args['c'] = '<div>hello world</div>';
        $args['foreach'] = [1, 2, 3, 4, 5, ];
        $args['for'] = [1, 2, 3, 4, 5, ];

        self::parse($path_v, $path_p);
        $file_b = self::build($args, $path_p);
        self::cache($path_c, $file_b);
        return $file_b;

    }


    private static function parse($path_v, $path_p)
    {

        $tag_list = [
            'include' => [
                [
                    'pattern' => '/@includeS([\s\S]*?)@includeE/',
                    'replace' => function($match_list)
                    {
                        $app = \Pf\App::getInstance();
                        return file_get_contents(
                            sprintf(
                                '%s%s.php',
                                $app->path_dir_view,
                                str_ireplace('.', DIRECTORY_SEPARATOR, trim($match_list[1]))
                            )
                        );
                    },
                ],
            ],
            'comment' => [
                [
                    'pattern' => '/@commentS([\s\S]*?)@commentE/',
                    'replace' => function($match_list)
                    {
                        return '';
                    },
                ],
            ],
            'php' => [
                [
                    'pattern' => '/@phpS([\s\S]*?)@phpE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php %s; ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'scalar' => [
                [
                    'pattern' => '/@scalarS([\s\S]*?)@scalarE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php echo %s; ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'escape' => [
                [
                    'pattern' => '/@escapeS([\s\S]*?)@escapeE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php echo htmlentities(%s); ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'json' => [
                [
                    'pattern' => '/@jsonS([\s\S]*?)@jsonE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php echo json_encode(%s); ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'foreach' => [
                [
                    'pattern' => '/@foreachS([\s\S]*?)@foreachE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php foreach (%s) { ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'endForeach' => [
                [
                    'pattern' => '/@endForeach/',
                    'replace' => function($match_list)
                    {
                        return '<?php } ?>';
                    },
                ],
            ],
            'for' => [
                [
                    'pattern' => '/@forS([\s\S]*?)@forE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php for (%s) { ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'endFor' => [
                [
                    'pattern' => '/@endFor/',
                    'replace' => function($match_list)
                    {
                        return '<?php } ?>';
                    },
                ],
            ],
            'while' => [
                [
                    'pattern' => '/@whileS([\s\S]*?)@whileE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php while (%s) { ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'endWhile' => [
                [
                    'pattern' => '/@endWhile/',
                    'replace' => function($match_list)
                    {
                        return '<?php } ?>';
                    },
                ],
            ],
            'if' => [
                [
                    'pattern' => '/@ifS([\s\S]*?)@ifE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php if (%s) { ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'elseIf' => [
                [
                    'pattern' => '/@elseIfS([\s\S]*?)@elseIfE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php } else if (%s) { ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'else' => [
                [
                    'pattern' => '/@else/',
                    'replace' => function($match_list)
                    {
                        return '<?php } else { ?>';
                    },
                ],
            ],
            'endIf' => [
                [
                    'pattern' => '/@endIf/',
                    'replace' => function($match_list)
                    {
                        return '<?php } ?>';
                    },
                ],
            ],
            'continue' => [
                [
                    'pattern' => '/@continue/',
                    'replace' => function($match_list)
                    {
                        return '<?php continue; ?>';
                    },
                ],
            ],
            'break' => [
                [
                    'pattern' => '/@break/',
                    'replace' => function($match_list)
                    {
                        return '<?php break; ?>';
                    },
                ],
            ],
            'switch' => [
                [
                    'pattern' => '/@switchS([\s\S]*?)@switchE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php switch (%s) { ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'case' => [
                [
                    'pattern' => '/\s*@caseS([\s\S]*?)@caseE/',
                    'replace' => function($match_list)
                    {
                        return sprintf(
                            '<?php case %s: ?>',
                            $match_list[1]
                        );
                    },
                ],
            ],
            'default' => [
                [
                    'pattern' => '/@default/',
                    'replace' => function($match_list)
                    {
                        return '<?php default: ?>';
                    },
                ],
            ],
            'endSwitch' => [
                [
                    'pattern' => '/@endSwitch/',
                    'replace' => function($match_list)
                    {
                        return '<?php } ?>';
                    },
                ],
            ],
        ];
        $file_c = file_get_contents($path_v);
        foreach($tag_list as $rule_list)
        {
            foreach($rule_list as $rule)
            {
                $file_c = preg_replace_callback($rule['pattern'], $rule['replace'], $file_c);
            }
        }
        Dir::createIfNotExists(dirname($path_p));
        file_put_contents($path_p, $file_c);

    }
    private static function build($args, $path_p)
    {

        if($args)
        {
            extract($args);
        }
        ob_start();
        require_once $path_p;
        $file_b = ob_get_contents();
        ob_end_clean();
        return $file_b;

    }
    private static function cache($path_c, $file_b)
    {

        Dir::createIfNotExists(dirname($path_c));
        file_put_contents($path_c, $file_b);

    }


}
