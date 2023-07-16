<?php

namespace UnitTest\Tool;

class Assert
{

    public static function same($expect, $get)
    {

        $trace_list = debug_backtrace();
        $trace = $trace_list[0];
        if ($expect === $get) {
            echo "\033[0;32m" . str_pad("success", 8, " ", STR_PAD_RIGHT) . "\033[0m" . $trace["file"] . " " . $trace["line"] . "\n";
            return;
        }
        echo "\033[31;31m" . str_pad("failure", 8, " ", STR_PAD_RIGHT) . $trace["file"] . " " . $trace["line"] . "\033[0m" . "\n";
        echo "\n";
        echo "\033[31;31m" . str_pad("expect", 8, " ", STR_PAD_RIGHT) . $expect . "\033[0m" . "\n";
        echo "\033[31;31m" . str_pad("get", 8, " ", STR_PAD_RIGHT) . $get . "\033[0m" . "\n";
        echo "\033[31;31m" . str_pad("trace", 8, " ", STR_PAD_RIGHT) . "\033[0m" . "\n";
        foreach ($trace_list as $trace) {
            echo "\033[31;31m" . str_pad("", 8, " ", STR_PAD_RIGHT) . $trace["file"] . " " . $trace["line"] . "\033[0m" . "\n";
        }
        echo "\n";
        exit();

    }

}
