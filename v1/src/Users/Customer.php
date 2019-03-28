<?php
namespace Bookshelf;

use Error\ApiProblem;
use Error\Exception\ProblemException;
use Zend\InputFilter\Factory as InputFilterFactory;

class Customer
{
    protected $customer_id;
    protected $customer_uuid;
    protected $country_code;
    protected $number_phone; //Seulement 9 chiffre, sans 243 par exemple
    protected $names;
    protected $email;
    protected $location;
    protected $password;
    protected $created;
    protected $updated;

    public function __construct(array $data)
    {
        $data = $this->validate($data);

        $this->customer_id = $data['customer_id'] ?? null;
        $this->customer_uuid = $data['customer_uuid'] ?? null;
        $this->country_code = $data['country_code'] ?? null;
        $this->number_phone = $data['number_phone'] ?? null;
        $this->names = $data['names'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->location = $data['location'] ?? null;
        $this->created = $data['created'] ?? null;
        $this->updated = $data['updated'] ?? null;


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
            'country_code', 
            'number_phone', 
            'names',
            'email',
            'password'
            ]);

        $this->country_uuid = $data['country_uuid'] ?? $this->country_uuid;
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
            'customer_uuid' => [
                'required' => true,
                'validators' => [
                    ['name' => 'Uuid'],
                ],
            ],
            'country_code' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'Uuid'],
                ],
            ],
            'number_phone' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'Uuid'],
                ],
            ],
            'names' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'email' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'EmailAdress'],
                    [
                        'name' => 'LessThan',
                        'options' => [
                            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck' => false,
                        ],
                    ],
                ],
            ],
            'password' => [
                'required' => true,
                'validators' => [
                    ['name' => 'not_empty'],
                    [
                        'name' => 'string_length',
                        'options' => [
                            'min' => 4,
                            'max' => 5,
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
