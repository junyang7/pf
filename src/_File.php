<?php

namespace Junyang7\PhpCommon;

class _File
{

    public static function exists($path)
    {

        return is_file($path);

    }

    public static function delete($path)
    {

        if (self::exists($path)) {
            if (false === unlink($path)) {
                throw new \Exception("文件删除失败");
            }
        }

    }

    public static function write($path, $content, $flags = FILE_APPEND)
    {

        _Directory::create(dirname($path));
        if (false === file_put_contents($path, $content, $flags)) {
            throw new \Exception("文件写入失败");
        }

    }

    public static function read($path)
    {

        if (!self::exists($path)) {
            throw new \Exception("文件不存在");
        }
        $res = file_get_contents($path);
        if (false === $res) {
            throw new \Exception("文件读取失败");
        }
        return $res;

    }

}
