<?php
namespace Maishapay\CustomersTest\Action;

use Maishapay\Customers\Action\CreateCustomerAction;
use Maishapay\Customers\Customer;
use Maishapay\Customers\CustomerMapper;
use Maishapay\Util\Utils;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class CreateCustomerActionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsJsonByDefault()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $renderer = new HalRenderer;

        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $uuid = Utils::uuid("customer");

        $mockData = [
            'customer_id' => 220,
            'customer_uuid' => $uuid,
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
            'updated' => $now,
                    ];
        $mockAuthor = new Customer($mockData);

        $authorMapper = $this->getMockBuilder(CustomerMapper::class)
            ->setMethods(['insert'])
            ->disableOriginalConstructor()
            ->getMock();

        $authorMapper->expects($this->once())
            ->method('insert')
            ->with($this->equalTo($mockAuthor))
            ->willReturn(true);

        $action = new CreateCustomerAction($logger, $renderer, $authorMapper);

        $request = Request::createFromEnvironment(new Environment());
        $request = $request->withParsedBody($mockData);
        $response = $action($request, new Response());

        $this->assertSame(201, $response->getStatusCode());
        $this->assertContains('application/hal+json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string)$response->getBody(), true);

        print json_encode($body, JSON_PRETTY_PRINT);
        $this->assertArrayHasKey('_links', $body);
        $this->assertSame('Jonathan Monga', $body['names']);
    }
}
