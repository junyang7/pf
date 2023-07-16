<?php

namespace UnitTest\File;

use Junyang7\PhpCommon\_Test;
use UnitTest\Tool\Assert;

class Test_test
{

    public static function test_test()
    {

        Assert::same("test", _Test::test());

    }

}
