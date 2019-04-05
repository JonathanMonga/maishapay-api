<?php
namespace Maishapay\CustomerTest;

use Maishapay\Customer\Customer;
use Maishapay\Customer\CustomerTransformer;
use Nocarrier\Hal;

class CustomerTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testTransform()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $data = [
            'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
            'name' => 'b',
            'biography' => 'c',
            'date_of_birth' => '1980-01-02',
            'created' => $now,
            'updated' => $now,

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
        $data = [
            'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
            'name' => 'b',
            'biography' => 'c',
            'date_of_birth' => '1980-01-02',
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
