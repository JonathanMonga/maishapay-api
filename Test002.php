<?php
/**
 * Created by PhpStorm.
 * User: davidkazad
 * Date: 23/11/2018
 * Time: 13:48
 */



class Test
{

}

require_once 'engine.php';
require_once 'Transaction.php';

$_POST['ent'] = 'taux';
$_POST['telephone'] = '099';

$transaction = new Transaction('android-client');