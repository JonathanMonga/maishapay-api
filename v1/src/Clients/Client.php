<?php
namespace Maishapay\Clients;

use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Util\Utils;
use Zend\InputFilter\Factory as InputFilterFactory;

class Client
{
    protected $client_uuid; //Client id
    protected $client_secret; //Client secret
    protected $client_status; //active_status or blocked_status
    protected $call_limit; //Call limit
    protected $redirect_uri; //Redirect uri
    protected $grant_types; //Grant type
    protected $scope; //Scope
    protected $customer_uuid; //User id
    protected $created;
    protected $updated;

    public function __construct(array $data)
    {
        $data = $this->validate($data);

        $this->client_uuid = $data['client_uuid'] ?? null;
        $this->client_secret = $data['client_secret'] ?? null;
        $this->client_status = $data['client_status'] ?? null;
        $this->call_limit = $data['call_limit'] ?? null;
        $this->redirect_uri = $data['redirect_uri'] ?? null;
        $this->grant_types = $data['grant_types'] ?? null;
        $this->scope = $data['scope'] ?? null;
        $this->customer_uuid = $data['customer_uuid'] ?? null;
        $this->created = $data['created'] ?? null;
        $this->updated = $data['updated'] ?? null;

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        if (!$this->client_uuid) {
            $this->client_uuid = Utils::uuid("client");
        }

        if (!$this->call_limit) {
            $this->call_limit = 100;
        }

        if (!$this->scope) {
            $this->scope = 'read_profil read_phone_number read_email';

        }

        if (!$this->client_status) {
            $this->client_status = 'blocked_status';
        }

        if (!strtotime($this->created)) {
            $this->created = $now;
        }

        if (!strtotime($this->updated)) {
            $this->updated = $now;
        }
    }

    public function getArrayCopy()
    {
        return [
            'client_uuid' => $this->client_uuid,
            'client_secret' => $this->client_secret,
            'client_status' => $this->client_status,
            'call_limit' => $this->call_limit,
            'redirect_uri' => $this->redirect_uri,
            'grant_types' => $this->grant_types,
            'scope' => $this->scope,
            'customer_uuid' => $this->customer_uuid,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }

    public function update($data)
    {
        $data = $this->validate($data, [
            'client_uuid',
            'client_secret',
            'client_status',
            'call_limit',
            'redirect_uri',
            ]);

        $this->client_uuid = $data['client_uuid'] ?? $this->client_uuid;
        $this->client_secret = $data['client_secret'] ?? $this->client_secret;
        $this->client_status = $data['client_status'] ?? $this->client_status;
        $this->call_limit = $data['call_limit'] ?? $this->call_limit;
        $this->redirect_uri = $data['redirect_uri'] ?? $this->redirect_uri;
    }

    /**
     * Validate data to be applied to this entity
     *
     * @param  array $data
     * @param array $elements
     * @return array
     */
    public function validate($data, $elements = [])
    {
        $inputFilter = $this->createInputFilter($elements);
        $inputFilter->setData($data);

        if ($inputFilter->isValid()) {
            return $inputFilter->getValues();
        }

        $problem = new ApiProblem(
            'Validation failed',
            'http://tools.ietf.org/html/rfc7231#section-6.6.1',
            400
        );

        $problem['errors'] = $inputFilter->getMessages();

        throw new ProblemException($problem);
    }

    protected function createInputFilter($elements = [])
    {
        $specification = [
            'client_uuid' => [
                'required' => true,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 45,
                        ],
                    ],
                ],
            ],
            'client_secret' => [
                'required' => true,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'client_status' => [
                'required' => true,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'call_limit' => [
                'required' => true,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'max' => 100,
                        ],
                    ],
                ],
            ],
            'redirect_uri' => [
                'required' => false,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ]
            ],
            'grant_types' => [
                'required' => false,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToLower'],
                    ['name' => 'StripTags'],
                ]
            ],
            'scope' => [
                'required' => true,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ]
            ],
            'customer_uuid' => [
                'required' => true,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 45,
                        ],
                    ],
                ],
            ],
            'created' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => 'Date',
                        'break_chain_on_failure' => true,
                        'options' => [
                            'format' => 'Y-m-d H:i:s',
                        ],
                    ],
                    [
                        'name' => 'LessThan',
                        'options' => [
                            'max' => date('Y-m-d H:i:s'),
                            'inclusive' => true,
                        ],
                    ],
                ],
            ],
            'updated' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => 'Date',
                        'break_chain_on_failure' => true,
                        'options' => [
                            'format' => 'Y-m-d H:i:s',
                        ],
                    ],
                    [
                        'name' => 'LessThan',
                        'options' => [
                            'max' => date('Y-m-d H:i:s'),
                            'inclusive' => true,
                        ],
                    ],
                ],
            ],
        ];

        if ($elements) {
            $specification = array_filter(
                $specification,
                function ($key) use ($elements) {
                    return in_array($key, $elements);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        $factory = new InputFilterFactory();
        $inputFilter = $factory->createInputFilter($specification);

        return $inputFilter;
    }
}
