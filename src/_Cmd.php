<?php

namespace Junyang7\PhpCommon;

class _Cmd
{

    public static function do($command)
    {

        if (false === ($proc = proc_open($command, [["pipe", "r",], ["pipe", "w",], ["pipe", "w",],], $pipe_list))) {
            throw new \Exception("proc_open操作失败");
        }
        if (false === fclose($pipe_list[0])) {
            throw new \Exception("流[0]关闭失败");
        }
        if (false === ($stdout = stream_get_contents($pipe_list[1]))) {
            throw new \Exception("流[1]读取失败");
        }
        if (false === fclose($pipe_list[1])) {
            throw new \Exception("流[1]关闭失败");
        }
        if (false === ($stderr = stream_get_contents($pipe_list[2]))) {
            throw new \Exception("流[2]读取失败");
        }
        if (false === fclose($pipe_list[2])) {
            throw new \Exception("流[2]关闭失败");
        }
        if (0 !== proc_close($proc)) {
            throw new \Exception($stderr);
        }
        return $stdout;

    }

}
