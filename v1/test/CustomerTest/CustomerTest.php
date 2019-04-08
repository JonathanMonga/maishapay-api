<?php
namespace Maishapay\CustomerTest;

use Maishapay\Customer\Customer;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructorProvider
     */
    public function testConstruction($inputData, $expectedData)
    {
        if (is_string($expectedData)) {
            $this->setExpectedException($expectedData);
        }

        $entity = new Customer($inputData);

        $newData = $entity->getArrayCopy();

        if (is_array($expectedData)) {
            unset($newData['created']);
            unset($newData['updated']);
            self::assertEquals($expectedData, $newData);
        }
    }

    public function constructorProvider()
    {
        return [
            'all-elements' => [
                [
                    'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
                    'name' => 'b',
                    'biography' => 'c',
                    'date_of_birth' => '1980-01-02',
                ],
                [
                    'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
                    'name' => 'b',
                    'biography' => 'c',
                    'date_of_birth' => '1980-01-02',
                ],
            ],
            'allowed-nulls' => [
                [
                    'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
                    'name' => 'b',
                    'biography' => null,
                    'date_of_birth' => null,
                ],
                [
                    'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
                    'name' => 'b',
                    'biography' => null,
                    'date_of_birth' => null,
                ],
            ],
            'string-trim' => [
                [
                    'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
                    'name' => ' b ',
                    'biography' => "\tc ",
                    'date_of_birth' => " 1980-01-02\n",
                ],
                [
                    'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
                    'name' => 'b',
                    'biography' => 'c',
                    'date_of_birth' => '1980-01-02',
                ],
            ],
            'date-of-bith-past' => [
                [
                    'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
                    'name' => 'b',
                    'biography' => 'c',
                    'date_of_birth' => date('Y-m-d', strtotime('+1 day')),
                ],
                'Maishapay\Error\Exception\ProblemException',
            ],
        ];
    }

    public function testDatesAreSet()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $entity = new Customer([
            'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
            'name' => 'b',
            'created' => $now,
            'updated' => $now,
        ]);
        $array = $entity->getArrayCopy();

        self::assertSame($now, $array['created']);
        self::assertSame($now, $array['updated']);
    }

    public function testDatesAreSetIfNull()
    {
        $entity = new Customer([
            'customer_id' => '2CB0681F-CCBE-417E-ADAD-19E9215EC58C',
            'name' => 'b',
        ]);

        $array = $entity->getArrayCopy();

        self::assertNotNull($array['created']);
        self::assertNotNull($array['updated']);
    }
}
