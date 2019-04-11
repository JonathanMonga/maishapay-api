<?php
/**
 * Created by PhpStorm.
 * User: maishapay
 * Date: 4/8/2019
 * Time: 6:49 PM
 */

namespace Maishapay\Util;

use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

class Utils
{
    public static function uuid($prefix = ''){

        try {
            $uuidString = $prefix.'-'.Uuid::uuid4()->toString();
        } catch (UnsatisfiedDependencyException $e) {
            $uuidString = uniqid($prefix, true);
        }

        return  $uuidString;
    }
}