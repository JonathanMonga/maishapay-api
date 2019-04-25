<?php
namespace Maishapay\CustomersTest;

use Maishapay\Customers\Customer;
use Maishapay\Util\Utils;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test testCustomer
     */
    public function testCustomer()
    {
        $uuid = Utils::uuid("customer");

        $customer = new Customer([
            'customer_id' => 220,
            'customer_uuid' => $uuid,
            'country_iso_code' => 'cd',
            'number_of_account' => 1,
            'location' => "Mpolo Lubumbashi",
            'phone_area_code' => '243',
            'number_phone' => '996980422',
            'customer_type' => 'particular',
            'customer_status' => 'activated',
            'names' => 'Jonathan Monga',
            'email' => 'jmonga98@gmail.com',
            'password' => '12345'
        ]);

        foreach ($customer->getArrayCopy() as $item)
            print $item."\n";
    }
}
