<?php

namespace Lontra\Validator;

use InvalidArgumentException;
use Laminas\Validator\AbstractValidator;

/**
 * The IsArray check if value is a valid array
 *
 * @license MIT https://opensource.org/licenses/MIT
 */
class IsArray extends AbstractValidator
{
    protected $options = [
        'emptyIsValid' => false,
        'nullIsValid' => false,
    ];

    const NOT_ARRAY       = 'notArray';
    const IS_EMPTY       = 'isEmpty';
    const IS_NULL       = 'isNull';

    protected $messageTemplates = [
        self::NOT_ARRAY           => "Value is not a valid array",
        self::IS_EMPTY           => "Value is empty",
        self::IS_NULL           => "Value is null",
    ];

    /**
     * Create a new IsArray instance with options
     *
     * @param array|int $options Array options with "emptyIsValid"(Or constructor parameter) and "nullIsValid" keys to allow 'empty' to be valid
     * 
     * @return void
     */
    public function __construct($options = false)
    {
        if (is_bool($options)) {
            $options = ['emptyIsValid' => $options];
        } else if (!is_array($options)) {
            throw new InvalidArgumentException("Invalid argument: Parameter passed is not an array or bool.");
        } else if (
            !empty($options)
            && !array_key_exists('emptyIsValid', $options)
            && !array_key_exists('nullIsValid', $options)
        ) {
            throw new InvalidArgumentException("Invalid argument: No arguments entered are valid.");
        }

        parent::__construct($options);
    }

    /**
     * Apply de validation
     * 
     * @param mixed $value
     * 
     * @return void
     */
    public function isValid($value)
    {
        $this->setValue($value);

        if(!$this->getNullIsValid() && is_null($this->getValue())){
            $this->error(self::IS_NULL);
            return false;
        }

        //Empty array only
        if(is_array($this->getValue()) && !$this->getEmptyIsValid() && empty($this->getValue())){
            $this->error(self::IS_EMPTY);
            return false;
        }

        if(!is_array($this->getValue())){
            if($this->getNullIsValid() && is_null($this->getValue())){
                return true;
            }
            $this->error(self::NOT_ARRAY);
            return false;
        }

        return true;
    }


    /**
     * Return "emptyIsValid" value
     *
     * @return bool
     */
    public function getEmptyIsValid(): ?bool
    {
        return $this->options['emptyIsValid'];
    }

    public function setEmptyIsValid($option)
    {
        $this->options['emptyIsValid'] = $option;

        return $this;
    }

    /**
     * Return "emptyIsValid" value
     *
     * @return bool
     */
    public function getNullIsValid(): ?bool
    {
        return $this->options['nullIsValid'];
    }

    public function setNullIsValid($option)
    {
        $this->options['nullIsValid'] = $option;

        return $this;
    }
    

    public function getValueInput()
    {
        return $this->getValue();
    }
}
