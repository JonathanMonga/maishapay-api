<?php
namespace Maishapay\CustomerTest;

use Maishapay\AppTest\Bootstrap;
use Maishapay\Customer\Customer;
use Maishapay\Customer\CustomerMapper;
use Monolog\Logger;

class CustomerMapperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    private function getMockLogger()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        return $logger;
    }

    public function testInsert()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $container = Bootstrap::getContainer();

        $db = $container->get('db');

        $customer = new Customer([
            'customer_id' => 220,
            'customer_uuid' => 'customer-814469d5-919f-4b67-9360-5b777b040c73',
            'country_iso_code' => 'cd',
            'number_of_account' => 1,
            'location' => "Mpolo Lubumbashi",
            'phone_area_code' => '243',
            'number_phone' => '996980422',
            'customer_type' => 'particular',
            'customer_status' => 'active_status',
            'names' => 'Jonathan Monga',
            'email' => 'jmonga98@gmail.com',
            'password' => '12345',
            'created' => '2017-01-28 22:00:01',
            'updated' => '2017-01-28 22:00:01',
        ]);

        $mapper = new CustomerMapper($logger, $db);
        $result = $mapper->insert($customer);

        self::assertInstanceOf(Customer::class, $result);

        // check that the updated property is more recent
        $newData = $result->getArrayCopy();
        self::assertGreaterThan('2017-01-28 22:00:01', $newData['updated']);
    }

    public function testFetchAll()
    {
        $logger = $this->getMockLogger();

        $container = \AppTest\Bootstrap::getContainer();
        $db = $container->get('db');

        $mapper = new CustomerMapper($logger, $db);
        $data = $mapper->fetchAll();

        $expected = new Customer([
            'customer_id' => 'f075512f-9734-304c-b839-b86174143c07',
            'name' => 'Ann McCaffrey',
            'biography' => "Anne Inez McCaffrey was an American-born Irish writer,"
                . " best known for the Dragonriders of Pern fantasy series. Early in"
                . " McCaffrey's 46-year career as a writer, she became the first"
                . " woman to win a Hugo Award for fiction and the first to win a"
                . " Nebula Award.",
            'date_of_birth' => '1926-04-01',
            'created' => '2017-01-28 22:00:00',
            'updated' => '2017-01-28 22:00:00',
        ]);
        self::assertEquals(2, count($data));
        self::assertEquals($expected, $data[0]);
    }

    public function testLoadById()
    {
        $logger = $this->getMockLogger();

        $container = \AppTest\Bootstrap::getContainer();
        $db = $container->get('db');

        $mapper = new CustomerMapper($logger, $db);
        $customer = $mapper->loadById('77707f1b-400c-3fe0-b656-c0b14499a71d');

        $expected = new Customer([
            'customer_id' => '77707f1b-400c-3fe0-b656-c0b14499a71d',
            'name' => 'Suzanne Collins',
            'biography' => 'Suzanne Marie Collins is an American television writer and novelist,'
                .' best known as the customer of The Underland Chronicles and The Hunger Games trilogy.',
            'date_of_birth' => '1962-08-10',
            'created' => '2017-01-28 22:00:00',
            'updated' => '2017-01-28 22:00:00',
        ]);
        self::assertEquals($expected, $customer);
    }

    public function testLoadByIdReturnsFalseOnFailure()
    {
        $logger = $this->getMockLogger();

        $container = \AppTest\Bootstrap::getContainer();
        $db = $container->get('db');

        $mapper = new CustomerMapper($logger, $db);
        $customer = $mapper->loadById('not-here');

        self::assertEquals(false, $customer);
    }

    public function testUpdate()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $container = \AppTest\Bootstrap::getContainer();
        $db = $container->get('db');

        $mapper = new CustomerMapper($logger, $db);
        $customer = $mapper->loadById('77707f1b-400c-3fe0-b656-c0b14499a71d');

        $customer->update(['name' => 'Someone Else']);
        $result = $mapper->update($customer);

        self::assertInstanceOf(Customer::class, $result);

        // Reload the customer from the database and check that it is updated
        $newCustomer = $mapper->loadById('77707f1b-400c-3fe0-b656-c0b14499a71d');
        $newData = $newCustomer->getArrayCopy();
        self::assertSame('Someone Else', $newData['name']);
        self::assertGreaterThanOrEqual(date('Y-m-d H:i:s'), $newData['updated']);
    }

    public function testDelete()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $container = \AppTest\Bootstrap::getContainer();
        $db = $container->get('db');

        $mapper = new CustomerMapper($logger, $db);

        // ensure record exists
        $customer = $mapper->loadById('77707f1b-400c-3fe0-b656-c0b14499a71d');

        $result = $mapper->delete('77707f1b-400c-3fe0-b656-c0b14499a71d');

        self::assertTrue($result);

        // Reload the customer from the database and ensure it fails
        $customer = $mapper->loadById('77707f1b-400c-3fe0-b656-c0b14499a71d');
        self::assertFalse($customer);
    }

    public function testDeleteOfNoRecord()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $container = \AppTest\Bootstrap::getContainer();
        $db = $container->get('db');

        $mapper = new CustomerMapper($logger, $db);
        $result = $mapper->delete('unknown-uuid');
        self::assertFalse($result);
    }
}
