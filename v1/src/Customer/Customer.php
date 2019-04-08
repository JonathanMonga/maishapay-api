<?php
namespace Maishapay\Customer;

use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Zend\InputFilter\Factory as InputFilterFactory;

class Customer
{
    protected $customer_id;
    protected $customer_uuid;
    protected $country_iso_code; //CD, US, ect... and user input
    protected $country_code; //243, 250, ect... and user input
    protected $number_phone; //Format 996980422 and user input
    protected $names; //user input
    protected $email; //user input
    protected $customer_type; //user input
    protected $number_of_account; //min 1 (current account), max 2 (current account | saving account)
    protected $location; //user input
    protected $password; //user input
    protected $customer_status; //active or blocked
    protected $created;
    protected $updated;

    public function __construct(array $data)
    {
        $data = $this->validate($data);

        $this->customer_id = $data['customer_id'] ?? null;
        $this->customer_uuid = $data['customer_uuid'] ?? null;
        $this->country_iso_code = $data['country_iso_code'] ?? null;
        $this->country_code = $data['country_code'] ?? null;
        $this->number_phone = $data['number_phone'] ?? null;
        $this->names = $data['names'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->customer_type = $data['customer_type'] ?? null;
        $this->number_of_account = $data['number_of_account'] ?? null;
        $this->customer_status = $data['customer_status'] ?? null;
        $this->location = $data['location'] ?? null;
        $this->created = $data['created'] ?? null;
        $this->updated = $data['updated'] ?? null;

        if(!$this->customer_uuid){
            $this->customer_uuid = uniqid("customer_", true);
        }

        if(!$this->customer_status){
            $this->customer_status = "blocked";
        }

        if(!$this->number_of_account) {
            $this->number_of_account = 1;
        }

        if(!$this->customer_type){
            $this->customer_type = "particular";
        }

        $now = (new \DateTime())->format('Y-m-d H:i:s');
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
            'customer_id' => $this->customer_id,
            'country_uuid' => $this->country_uuid,
            'country_iso_code' => $this->country_iso_code,
            'customer_type' => $this->customer_type,
            'number_of_account' => $this->number_of_account,
            'customer_status' => $this->customer_status,
            'country_code' => $this->country_code,
            'number_phone' => $this->number_phone,
            'names' => $this->names,
            'email' => $this->email,
            'password' => $this->password,
            'location' => $this->location,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }

    public function update($data)
    {
        $data = $this->validate($data, [
            'country_uuid',
            'country_iso_code',
            'country_code', 
            'number_phone',
            'customer_type',
            'names',
            'email',
            'password'
            ]);

        $this->country_uuid = $data['country_uuid'] ?? $this->country_uuid;
        $this->country_iso_code = $data['country_iso_code'] ?? $this->country_iso_code;
        $this->country_code = $data['country_code'] ?? $this->country_code;
        $this->number_phone = $data['number_phone'] ?? $this->number_phone;
        $this->customer_type = $data['customer_type'] ?? $this->customer_type;
        $this->names = $data['names'] ?? $this->names;
        $this->email = $data['email'] ?? $this->email;
        $this->password = $data['password'] ?? $this->password;
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
            'about:blank',
            400
        );
        $problem['errors'] = $inputFilter->getMessages();

        throw new ProblemException($problem);
    }

    protected function createInputFilter($elements = [])
    {
        $specification = [
            'country_uuid' => [
                'required' => false,
                'validators' => [
                    ['name' => 'Uuid'],
                ],
            ],
            'country_iso_code' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'not_empty'],
                    [
                        'name' => 'string_length',
                        'options' => [
                            'min' => 2,
                        ],
                    ],
                ],
            ],
            'country_code' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'not_empty'],
                    [
                        'name' => 'string_length',
                        'options' => [
                            'min' => 3,
                        ],
                    ],
                ],
            ],
            'number_phone' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'not_empty'],
                    [
                        'name' => 'string_length',
                        'options' => [
                            'min' => 9,
                            'max' => 13,
                        ],
                    ],
                ],
            ],
            'customer_type' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'date_of_birth' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'Date'],
                    [
                        'name' => 'LessThan',
                        'options' => [
                            'max' => date('Y-m-d'),
                            'inclusive' => true,
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
