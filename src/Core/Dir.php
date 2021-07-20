<?php


namespace Pf\Core;


class Dir
{


    public static function getFileList($dir, $deep = 0, $extend = '', $extend_length = 0, $recursion = FALSE)
    {

        static $file_list = [];

        if($deep == 0)
        {
            $file_list = [];
        }

        foreach(scandir($dir) as $file)
        {

            if($file != '.' && $file != '..')
            {

                $path = rtrim($dir, "\0\t\n\x0B\r " . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

                if(!empty($extend))
                {

                    if(substr($file, -$extend_length) == $extend)
                    {
                        $file_list[$deep][$file] = $path;
                    }

                }
                else
                {
                    $file_list[$deep][$file] = $path;
                }

                if(is_dir($path))
                {

                    if($recursion)
                    {
                        self::getFileList($path, $deep + 1, $extend, $recursion);
                    }

                }

            }

        }

        return $file_list;

    }


    public static function createIfNotExists($dir)
    {

        if(!file_exists($dir))
        {
            mkdir($dir, 0777, TRUE);
        }

    }


}
