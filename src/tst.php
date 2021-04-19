<?php


namespace CodexSoft\Transmission\Schema;


class tst
{
    public static function x()
    {
        Is::number()->nullable()->label('')->lt(15);
        Is::num2()->notNull()->gt(100)->label('')->lte(1000);
        //Is::number()->lte
    }
}
