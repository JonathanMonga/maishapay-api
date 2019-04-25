<?php
namespace Maishapay\CustomersTest\Action;


use Maishapay\Customers\Action\DeleteCustomerAction;
use Maishapay\Customers\Customer;
use Maishapay\Customers\CustomerMapper;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Util\Utils;
use Monolog\Logger;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class DeleteCustomerActionTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthorIsDeleted()
    {
        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $renderer = new HalRenderer;

        $uuid = Utils::uuid("customer");

        $customer_uuid = $uuid;

        $authorMapper = $this->getMockBuilder(CustomerMapper::class)
            ->setMethods(['loadById', 'delete'])
            ->disableOriginalConstructor()
            ->getMock();

        $authorMapper->expects($this->once())
            ->method('loadById')
            ->with($this->equalTo($customer_uuid))
            ->willReturn(new Customer([
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
                'password' => '12345'
                ]));

        $authorMapper->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($customer_uuid))
            ->willReturn(1);

        $action = new DeleteCustomerAction($logger, $renderer, $authorMapper);

        $request = Request::createFromEnvironment(new Environment());
        $request = $request->withAttribute('customer_uuid', $customer_uuid);

        $response = $action($request, new Response());

        self::assertSame(204, $response->getStatusCode());
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

        $action = new DeleteCustomerAction($logger, $renderer, $authorMapper);

        $request = Request::createFromEnvironment(new Environment());
        $request = $request->withAttribute('customer_uuid', 'unknown-uuid');

        $this->expectException(ProblemException::class);
        $response = $action($request, new Response());
    }
}
