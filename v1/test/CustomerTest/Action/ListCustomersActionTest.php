<?php
namespace Maishapay\CustomerTest\Action;

use Maishapay\Customer\Action\GetAllCustomersAction;
use Maishapay\Customer\Customer;
use Maishapay\Customer\CustomerMapper;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class ListCustomersActionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsJsonBydefault()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $renderer = new HalRenderer;

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $mockData = [
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
            'created' => $now,
            'updated' => $now
                ];
        $mockAuthors = [
            new Customer($mockData),
        ];

        $AuthorMapper = $this->getMockBuilder(CustomerMapper::class)
            ->setMethods(['fetchAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $AuthorMapper->method('fetchAll')->willReturn($mockAuthors);

        $action = new GetAllCustomersAction($logger, $renderer, $AuthorMapper);

        $environment = new Environment([
            'REQUEST_URI' => '/Customer'
        ]);

        $response = $action(
            Request::createFromEnvironment($environment),
            new Response()
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertContains('application/hal+json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string)$response->getBody(), true);
        self::assertArrayHasKey('_links', $body);

        $expectedData = $mockData;
        $expectedData['_links'] = [
            'self' => [
                'href' => '/customers/' . $mockData['customer_uuid'],
            ],
            'books' => [
                'href' => '/customers/' . $mockData['customer_uuid'] . '/accounts',
            ],
        ];

        self::assertSame($expectedData, $body['_embedded']['customer'][0]);
    }
}
