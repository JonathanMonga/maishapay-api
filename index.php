<?php
/**
 * Created by PhpStorm.
 * User: davidkazad
 * Date: 23/11/2018
 * Time: 13:48
 */


require_once 'engine.php';
require_once 'Transaction.php';

$transaction = new Transaction('android-client');
if (isset($_POST['client']) && $_POST['client']=='web'){

}else {
    echo json_encode($transaction->result());
}