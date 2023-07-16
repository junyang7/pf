<?php

namespace Junyang7\PhpCommon;

class _Shard
{

    public static function default($id, $database_count, $table_count)
    {

        $m = $id % ($database_count * $table_count);
        $database_index = intval($m / $table_count);
        $table_index = $m % $table_count;
        $res = [];
        $res["database_index"] = $database_index;
        $res["table_index"] = $table_index;
        return $res;

    }

}
