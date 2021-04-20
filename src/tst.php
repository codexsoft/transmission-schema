<?php


namespace CodexSoft\Transmission\Schema;


class tst
{
    public static function x()
    {
        Is::number()->nullable()->label('')->lt(15);
        Is::num2()->notNull()->gt(100)->label('')->lte(1000);
        Is::test()->label()->notNull()->deprecated()->nullable()->nullable();
        Is::json()->label()->modeDeny()->denyExtraFields()->optional()->type();
        Is::email()->notBlank()->length()->minLength(100);
        //Is::number()->lte
    }
}
