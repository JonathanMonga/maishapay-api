<?php
namespace Maishapay\CustomersTest\Action;

use Maishapay\Customers\Action\GetCustomerByUUIDAction;
use Maishapay\Customers\Customer;
use Maishapay\Customers\CustomerMapper;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Util\Utils;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class GetCustomerByUUIDActionTest extends \PHPUnit_Framework_TestCase
{

    public function testReturnsJsonByDefault()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $renderer = new HalRenderer;

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $mockData = new Customer([
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
            'updated' => $now]);

        $customerMapper = $this->getMockBuilder(CustomerMapper::class)
            ->setMethods(['loadById'])
            ->disableOriginalConstructor()
            ->getMock();

        $customerMapper->expects($this->once())
            ->method('loadById')
            ->with($this->equalTo('customer-814469d5-919f-4b67-9360-5b777b040c73'))
            ->willReturn($mockData);

        $action = new GetCustomerByUUIDAction($logger, $renderer, $customerMapper);

        $request = Request::createFromEnvironment(new Environment());
        $request = $request->withAttribute('customer_uuid', 'customer-814469d5-919f-4b67-9360-5b777b040c73');

        $response = $action($request, new Response());

        $this->assertContains('application/hal+json', $response->getHeaderLine('Content-Type'));

        $body = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('_links', $body);
        $this->assertSame('Jonathan Monga', $body['names']);
    }

    public function testThrowsExceptionOnNotFound()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $renderer = new HalRenderer;

        $customerMapper = $this->getMockBuilder(CustomerMapper::class)
            ->setMethods(['loadById'])
            ->disableOriginalConstructor()
            ->getMock();

        $customerMapper->expects($this->once())
            ->method('loadById')
            ->with($this->equalTo('customer-814469d5-919f-4b67-9360-5b777b040c73'))
            ->willReturn(false);

        $action = new GetCustomerByUUIDAction($logger, $renderer, $customerMapper);

        $request = Request::createFromEnvironment(new Environment());
        $request = $request->withAttribute('customer_uuid', 'customer-814469d5-919f-4b67-9360-5b777b040c73');

        $this->expectException(ProblemException::class);
        $response = $action($request, new Response());
    }
}
