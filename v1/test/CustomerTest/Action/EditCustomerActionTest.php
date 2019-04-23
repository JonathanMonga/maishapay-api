<?php
namespace BookshelfTest\Action;

use Maishapay\Customer\Action\EditCustomerAction;
use Maishapay\Customer\Customer;
use Maishapay\Customer\CustomerMapper;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Util\Utils;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class EditCustomerActionTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthorIsUpdated()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $renderer = new HalRenderer;

        $uuid = Utils::uuid("customer");

        $customer_uuid = $uuid;

        $mockData = [
            'customer_id' => 220,
            'customer_uuid' => $customer_uuid,
            'country_iso_code' => 'cd',
            'number_of_account' => 1,
            'location' => "Mpolo Lubumbashi",
            'phone_area_code' => '243',
            'number_phone' => '996980422',
            'customer_type' => 'particular',
            'customer_status' => 'active_status',
            'names' => 'Jonathan Monga',
            'email' => 'jmonga98@gmail.com',
            'password' => '12345'
        ];

        $mockAuthor = new Customer($mockData);

        $authorMapper = $this->getMockBuilder(CustomerMapper::class)
            ->setMethods(['loadById', 'update'])
            ->disableOriginalConstructor()
            ->getMock();

        $authorMapper->expects($this->once())
            ->method('loadById')
            ->with($this->equalTo($customer_uuid))
            ->willReturn($mockAuthor);

        $authorMapper->expects($this->once())
            ->method('update')
            ->with($this->equalTo($mockAuthor))
            ->willReturn($mockAuthor);

        $action = new EditCustomerAction($logger, $renderer, $authorMapper);

        $request = Request::createFromEnvironment(new Environment());
        $request = $request->withAttribute('customer_uuid', $customer_uuid);
        $request = $request->withParsedBody($mockData);

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

        $authorMapper = $this->getMockBuilder(CustomerMapper::class)
            ->setMethods(['loadById'])
            ->disableOriginalConstructor()
            ->getMock();
        $authorMapper->expects($this->once())
            ->method('loadById')
            ->with($this->equalTo('unknown-uuid'))
            ->willReturn(false);

        $action = new EditCustomerAction($logger, $renderer, $authorMapper);

        $request = Request::createFromEnvironment(new Environment());
        $request = $request->withAttribute('customer_uuid', 'unknown-uuid');

        $this->expectException(ProblemException::class);
        $response = $action($request, new Response());
    }
}
