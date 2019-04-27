<?php
namespace Maishapay\Accounts;

use Maishapay\Error\ApiProblem;
use Maishapay\Error\Exception\ProblemException;
use Maishapay\Util\Utils;
use Zend\InputFilter\Factory as InputFilterFactory;

class Account
{
    protected $account_id;
    protected $account_uuid;
    protected $customer_uuid;
    protected $account_type; //Current(Wallet) and Saving
    protected $default_balance;
    protected $default_currency; //Ex : USD
    protected $local_balance;
    protected $local_currency; //Ex : CDF
    protected $default_balance_sent;
    protected $default_balance_receive;
    protected $local_balance_sent;
    protected $local_balance_receive;
    protected $account_status;
    protected $last_transfer;
    protected $saving_start_day;
    protected $saving_end_day;
    protected $created;
    protected $updated;

    public function __construct(array $data)
    {
        $data = $this->validate($data);

        $this->account_id = $data['account_id'] ?? null;
        $this->account_uuid = $data['account_uuid'] ?? null;
        $this->customer_uuid = $data['customer_uuid'] ?? null;
        $this->account_type = $data['account_type'] ?? null;
        $this->default_balance = $data['default_balance'] ?? null;
        $this->default_currency = $data['default_currency'] ?? null;
        $this->local_balance = $data['local_balance'] ?? null;
        $this->local_currency = $data['local_currency'] ?? null;
        $this->default_balance_sent = $data['default_balance_sent'] ?? null;
        $this->default_balance_receive = $data['default_balance_receive'] ?? null;
        $this->local_balance_sent = $data['local_balance_sent'] ?? null;
        $this->local_balance_receive = $data['local_balance_receive'] ?? null;
        $this->account_status = $data['account_status'] ?? null;
        $this->last_transfer = $data['last_transfer'] ?? null;
        $this->saving_start_day = $data['saving_start_day'] ?? null;
        $this->saving_end_day = $data['saving_end_day'] ?? null;
        $this->created = $data['created'] ?? null;
        $this->updated = $data['updated'] ?? null;

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        if (!$this->account_uuid) {
            $this->account_uuid = Utils::uuid("account-id");
        }

        if (!$this->account_type) {
            $this->account_type = "current";
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
            'account_id' => $this->account_id,
            'account_uuid' => $this->account_uuid,
            'customer_uuid' => $this->customer_uuid,
            'account_type' => $this->account_type,
            'default_balance' => $this->default_balance,
            'default_currency' => $this->default_currency,
            'local_balance' => $this->local_balance,
            'local_currency' => $this->local_currency,
            'default_balance_sent' => $this->default_balance_sent,
            'default_balance_receive' => $this->default_balance_receive,
            'local_balance_sent' => $this->local_balance_sent,
            'local_balance_receive' => $this->local_balance_receive,
            'account_status' => $this->account_status,
            'last_transfer' => $this->last_transfer,
            'saving_start_day' => $this->saving_start_day,
            'saving_end_day' => $this->saving_end_day,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }

    public function update($data)
    {
        $data = $this->validate($data, [
            'account_uuid',
            'account_type',
            'default_balance',
            'default_currency',
            'local_balance',
            'local_currency',
            'default_balance_sent',
            'default_balance_receive',
            'local_balance_sent',
            'local_balance_receive',
            'account_status',
            'last_transfer',
            'saving_start_day',
            'saving_end_day'
            ]);

        $this->account_uuid = $data['account_uuid'] ?? $this->account_uuid;
        $this->account_type = $data['account_type'] ?? $this->account_type;
        $this->default_balance = $data['default_balance'] ?? $this->default_balance;
        $this->default_currency = $data['default_currency'] ?? $this->default_currency;
        $this->local_balance = $data['local_balance'] ?? $this->local_balance;
        $this->local_currency = $data['local_currency'] ?? $this->local_currency;
        $this->default_balance_sent = $data['default_balance_sent'] ?? $this->default_balance_sent;
        $this->default_balance_receive = $data['default_balance_receive'] ?? $this->default_balance_receive;
        $this->local_balance_sent = $data['local_balance_sent'] ?? $this->local_balance_sent;
        $this->local_balance_receive = $data['local_balance_receive'] ?? $this->local_balance_receive;
        $this->account_status = $data['account_status'] ?? $this->account_status;
        $this->last_transfer = $data['last_transfer'] ?? $this->last_transfer;
        $this->saving_start_day = $data['saving_start_day'] ?? $this->saving_start_day;
        $this->saving_end_day = $data['saving_end_day'] ?? $this->saving_end_day;
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
            'account_id' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'default_balance_receive' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'local_balance_sent' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'local_balance_receive' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'account_status' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'last_transfer' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'saving_start_day' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'saving_end_day' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'account_uuid' => [
                'required' => false,
                'allowEmpty' => true,
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
            'customer_uuid' => [
                'required' => false,
                'allowEmpty' => true,
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
            'account_type' => [
                'required' => false,
                'allowEmpty' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'default_balance' => [
                'required' => false,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
            ],
            'default_currency' => [
                'required' => false,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ]
            ],
            'local_balance' => [
                'required' => true,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ]
            ],
            'local_currency' => [
                'required' => true,
                'allowEmpty' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ]
            ],
            'default_balance_sent' => [
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
