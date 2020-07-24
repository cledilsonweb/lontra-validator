<?php

namespace Lontra\Validator;

use InvalidArgumentException;
use Laminas\Validator\AbstractValidator;

/**
 * The EndWith class
 *
 * @license MIT https://opensource.org/licenses/MIT
 */
class EndWith extends AbstractValidator
{
    protected $options = [
        'end' => '',
        'caseSensitive'    => false,
    ];

    const NOT_END  = 'notEnd';
    const NOT_END_CASE  = 'notEndCase';

    protected $messageTemplates = [
        self::NOT_END      => "Value does not end with '%end%'",
        self::NOT_END_CASE      => "Value does not end with '%end%' considering upper and lower case",
    ];

    protected $messageVariables = [
        'end' => ['options' => 'end'],
    ];

    /**
     * Create a new EndWith instance with options
     *
     * @param array|string $options Array options  keys and values
     * 
     * @return void
     */
    public function __construct($options = null)
    {
        if (!empty($options) && is_string($options)) {
            $options = ['end' => $options];
        } else if (!is_array($options)) {
            throw new InvalidArgumentException("Invalid argument: Parameter passed is not an array or string.");
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

        $endLength = strlen($this->getEnd());
        $valueLength = strlen($this->getValue());
        $substr = substr($this->getValue(), ($valueLength - $endLength), $endLength);

        if ($this->getCaseSensitive()) {
            if (strcmp($substr, $this->getEnd()) != 0) {
                $this->error(self::NOT_END_CASE);
                return false;
            }
        } else {
            if (strcasecmp($substr, $this->getEnd()) != 0) {
                $this->error(self::NOT_END);
                return false;
            }
        }

        return true;
    }

    /**
     * Get the value of option
     *
     * @return bool
     */
    public function getEnd(): string
    {
        return $this->options['end'];
    }

    /**
     * Set the value of option
     *
     * @return self
     */
    public function setEnd($option)
    {
        $this->options['end'] = $option;

        return $this;
    }

    /**
     * Get the value of option
     *
     * @return bool
     */
    public function getCaseSensitive(): bool
    {
        return $this->options['caseSensitive'];
    }

    /**
     * Set the value of option
     *
     * @return self
     */
    public function setCaseSensitive($option)
    {
        $this->options['caseSensitive'] = $option;

        return $this;
    }
}
