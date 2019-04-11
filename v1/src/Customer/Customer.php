<?php
namespace Maishapay\Customer;

use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Util\Utils;
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

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        if (!$this->customer_type) {
            $this->customer_type = "particular";
        }

        if (!$this->number_of_account) {
            $this->number_of_account = 1;
        }

        if (!$this->customer_status) {
            $this->customer_status = 'blocked';
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
            'customer_id' => $this->customer_id,
            'customer_uuid' => $this->customer_uuid,
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
            'customer_uuid',
            'country_iso_code',
            'country_code', 
            'number_phone',
            'names',
            'email',
            'password'
            ]);

        $this->customer_uuid = $data['customer_uuid'] ?? $this->customer_uuid;
        $this->country_iso_code = $data['country_iso_code'] ?? $this->country_iso_code;
        $this->country_code = $data['country_code'] ?? $this->country_code;
        $this->number_phone = $data['number_phone'] ?? $this->number_phone;
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
            'customer_id' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'location' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'number_of_account' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'customer_status' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'customer_uuid' => [
                'required' => false,
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
            'country_iso_code' => [
                'required' => false,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToLower'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 2,
                        ],
                    ],
                ],
            ],
            'country_code' => [
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
                            'min' => 3,
                        ],
                    ],
                ],
            ],
            'number_phone' => [
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
                            'min' => 9,
                        ],
                    ],
                ],
            ],
            'customer_type' => [
                'required' => false,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'names' => [
                'required' => false,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'email' => [
                'required' => true,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToLower'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress'],
                ],
            ],
            'password' => [
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
                            'min' => 4,
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
