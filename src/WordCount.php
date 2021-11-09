<?php

namespace Lontra\Validator;

use InvalidArgumentException;
use Laminas\Validator\AbstractValidator;

/**
 * The WordCount class checks whether the value is between 
 * the "min" and "max" or equal "total".
 * Valid email has the same value as a word.
 *
 * @license MIT https://opensource.org/licenses/MIT
 */
class WordCount extends AbstractValidator
{
    protected $options = [
        'min'       => 0,
        'max'       => 0,
        'total'    => 0,
    ];

    const NOT_MIN       = 'notMin';
    const NOT_MAX       = 'notMax';
    const NOT_TOTAL       = 'notTotal';

    protected $messageTemplates = [
        self::NOT_MIN           => "Number of words must be greater than '%min%'. Input value is '%value%'",
        self::NOT_MAX    => "Number of words must be less than '%max%'. Input value is '%value%'",
        self::NOT_TOTAL         => "Number of words must equal '%total%'. Input value is '%value%'",
    ];

    protected $messageVariables = [
        'min' => ['options' => 'min'],
        'max' => ['options' => 'max'],
        'total' => ['options' => 'total'],
    ];


    /**
     * Create a new WordCount instance with options
     * Valid email has the same value as a word
     *
     * @param array|int $options Array options with "min", "max" and "total" keys and values or int value for "total"
     * 
     * @return void
     */
    public function __construct($options = null)
    {
        if (is_int($options)) {
            $options = ['total' => $options];
        } else if (!is_array($options)) {
            throw new InvalidArgumentException("Invalid argument: Parameter passed is not an array or int.");
        } else if (!empty($options)
            && !array_key_exists('min', $options)
            && !array_key_exists('max', $options)
            && !array_key_exists('total', $options)
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
        $count = $this->getWordCount($this->getValue());

        if ($this->getTotal() > 0) {
            if ($this->getTotal() != $count) {
                $this->error(self::NOT_TOTAL, $count);
                return false;
            }
            return true;
        }

        $valid = true;

        if ($this->getMin() > 0) {
            if ($count < $this->getMin()) {
                $this->error(self::NOT_MIN, $count);
                $valid = false;
            }
        }
        if ($this->getMax() > 0) {
            if ($count > $this->getMax()) {
                $this->error(self::NOT_MAX, $count);
                $valid = false;
            }
        }

        return $valid;
    }

    private function getWordCount($value): ?int
    {
        $reg = '/([a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+)|(\b\w*[-\']\w*\b)|(\w+)/u';
        return preg_match_all($reg, $value);
    }

    /**
     * Return "min"
     *
     * @return string
     */
    public function getMin(): ?int
    {
        return $this->options['min'];
    }

    public function setMin($option)
    {
        $this->options['min'] = $option;

        return $this;
    }

    /**
     * Return "max"
     *
     * @return string
     */
    public function getMax(): ?int
    {
        return $this->options['max'];
    }

    public function setMax($option)
    {
        $this->options['max'] = $option;

        return $this;
    }

    /**
     * Return "total"
     *
     * @return string
     */
    public function getTotal(): int
    {
        return $this->options['total'];
    }

    public function setTotal($option)
    {
        $this->options['total'] = $option;

        return $this;
    }

    public function getValueInput()
    {
        return $this->getValue();
    }
}
