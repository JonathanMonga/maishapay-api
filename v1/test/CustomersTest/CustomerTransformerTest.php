<?php
namespace Maishapay\CustomersTest;

use Maishapay\Customers\Customer;
use Maishapay\Customers\CustomerTransformer;
use Maishapay\Util\Utils;
use Nocarrier\Hal;

class CustomerTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $uuid = Utils::uuid("customer");

        $data = [
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
            'password' => '12345',
            'created' => $now,
            'updated' => $now
        ];

        $customer = new Customer($data);

        $transformer = new CustomerTransformer();
        $hal = $transformer->transform($customer);

        self::assertInstanceOf(Hal::class, $hal);

        $halData = $hal->getData();

        self::assertSame($data, $halData);
    }

    public function testTransformCollection()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $uuid = Utils::uuid("customer");

        $data = [
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
            'password' => '12345',
            'created' => $now,
            'updated' => $now,

        ];

        $customer = new Customer($data);
        $customers = [$customer];

        $transformer = new CustomerTransformer();
        $hal = $transformer->transformCollection($customers);

        self::assertInstanceOf(Hal::class, $hal);

        $halData = $hal->getData();
        self::assertSame(1, $halData['count']);

        $halResources = $hal->getResources();

        self::assertArrayHasKey('customer', $halResources);
        self::assertInstanceOf(Hal::class, $halResources['customer'][0]);
    }
}
