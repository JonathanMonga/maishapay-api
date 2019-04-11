<?php
namespace Maishapay\CustomerTest;

use Maishapay\Customer\Customer;
use Maishapay\Util\Utils;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test testCustomer
     */
    public function testCustomer()
    {
        new Customer([
            'country_uuid' => Utils::uuid("customer"),
            'country_iso_code' => 'cd',
            'country_code' => '243',
            'number_phone' => '996980422',
            'customer_type' => 'particular',
            'names' => 'Jonathan Monga',
            'email' => 'jmonga98@gmail.com',
            'password' => '12345'
        ]);
    }
}
