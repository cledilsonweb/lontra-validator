<?php

namespace Lontra\Validator;

use InvalidArgumentException;
use Laminas\Validator\AbstractValidator;

/**
 * The StartWith class
 *
 * @license MIT https://opensource.org/licenses/MIT
 */
class StartWith extends AbstractValidator
{
    protected $options = [
        'start' => '',
        'caseSensitive'    => false,
    ];

    const NOT_START  = 'notStart';
    const NOT_START_CASE  = 'notStartCase';

    protected $messageTemplates = [
        self::NOT_START      => "Value does not start with '%start%'",
        self::NOT_START_CASE      => "Value does not start with '%start%' considering upper and lower case",
    ];

    protected $messageVariables = [
        'start' => ['options' => 'start'],
    ];


    /**
     * Create a new StartWith instance with options
     *
     * @param array|string $options Array options  keys and values
     * 
     * @return void
     */
    public function __construct($options = null)
    {
        if (!empty($options) && is_string($options)) {
            $options = ['start' => $options];
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

        $startLength = strlen($this->getStart());
        $substr = substr($this->getValue(), 0, $startLength);

        if ($this->getCaseSensitive()) {
            if (strcmp($substr, $this->getStart()) != 0) {
                $this->error(self::NOT_START_CASE);
                return false;
            }
        } else {
            if (strcasecmp($substr, $this->getStart()) != 0) {
                $this->error(self::NOT_START);
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
    public function getStart(): string
    {
        return $this->options['start'];
    }

    /**
     * Set the value of option
     *
     * @return self
     */
    public function setStart($option)
    {
        $this->options['start'] = $option;

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
