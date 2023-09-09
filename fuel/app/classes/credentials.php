<?php

class Credentials
{

    protected static $secret_key = '6"BsUQ.q520)vORfp4U/!7DVvBIc4#';
    protected static $hash_password = "Y2+8Iu!u'!9O7UF5?0^fy[3/%B0LiJx/-42K2+BDj8bc`y`.7h6'Rfp4U/!7DVvBIc4')<uu{83YU_21Qz=|9|pcBWw(Z7";

    public static function hash_password($password)
    {
        return base64_encode(hash_pbkdf2('sha256', $password, self::$hash_password, 10000, 32, true));
    }
}
