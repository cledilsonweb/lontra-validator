<?php

namespace Lontra\Validator;

use InvalidArgumentException;
use Laminas\Validator\AbstractValidator;
use Lontra\Validator\Exception\InvalidDateException;

/**
 * The DateBetween class checks whether the value is between 
 * the "min" and "max" dates using "format" for formatting
 *
 * @license MIT https://opensource.org/licenses/MIT
 */
class DateBetween extends AbstractValidator
{
    protected $options = [
        'inclusive' => true,
        'min'       => 'now',
        'max'       => 'now',
        'format'    => 'Y-m-d',
    ];

    const NOT_BETWEEN       = 'notBetween';
    const NOT_BETWEEN_INCLUSIVE = 'notBetweenInclusive';
    const IVALID_FORMAT     = 'invalidFormat';
    const IVALID_DATE       = 'invalidDate';

    protected $messageTemplates = [
        self::NOT_BETWEEN           => "The input is not between '%min%' and '%max%'",
        self::NOT_BETWEEN_INCLUSIVE    => "The input is not between(inclusive) '%min%' and '%max%'",
        self::IVALID_FORMAT         => "The date is not in '%format%' format",
        self::IVALID_DATE           => "The parsed date was invalid",
    ];

    protected $messageVariables = [
        'min' => ['options' => 'min'],
        'max' => ['options' => 'max'],
    ];


    /**
     * Create a new DateBetween instance with options
     *
     * @param array $options Array options with "min", "max" and "inclusive" keys and values
     * 
     * @return void
     */
    public function __construct(array $options = null)
    {
        if (!is_array($options)) {
            throw new InvalidArgumentException("Invalid argument: Parameter passed is not an array.");
        }

        parent::__construct($options);
    }

    /**
     * Validate an return a bool
     *
     * @param mixed $value
     * 
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        // setting "now" date from format
        if ($this->getMin() == 'now') {
            $now = new \DateTime();
            $this->setMin($now->format($this->getFormat()));
        }
        if ($this->getMax() == 'now') {
            $now = new \DateTime();
            $this->setMin($now->format($this->getFormat()));
        }

        $minDate = \DateTime::createFromFormat($this->getFormat(), $this->getMin());
        if (!$this->validateDate($this->getFormat(), $this->getMin(), $minDate)) {
            throw new InvalidDateException("Invalid: 'min'({$this->getMin()}) format is different from '{$this->getFormat()}' \"format\".");
        }

        $maxDate = \DateTime::createFromFormat($this->getFormat(), $this->getMax());
        if (!$this->validateDate($this->getFormat(), $this->getMax(), $maxDate)) {
            throw new InvalidDateException("Invalid: 'max'({$this->getMax()}) format is different from '{$this->getFormat()}' \"format\".");
        }

        $date = \DateTime::createFromFormat($this->getFormat(), $this->getValue());
        if (!$this->validateDate($this->getFormat(), $this->getValue(), $date) || \DateTime::getLastErrors()['warning_count'] > 0) {
            $this->error(self::IVALID_DATE);
            return false;
        }

        if ($this->getInclusive()) {
            if ($date < $minDate || $date > $maxDate) {
                $this->error(self::NOT_BETWEEN_INCLUSIVE);
                return false;
            }
        } else {
            if ($date <= $minDate || $date >= $maxDate) {
                $this->error(self::NOT_BETWEEN);
                return false;
            }
        }
        return true;
    }

    /**
     * Validate a Date using the 'format'
     *
     * @param string    $format
     * @param string    $date
     * @param \DateTime $dateObject
     * 
     * @return bool
     */
    protected function validateDate($format, $date, $dateObject): bool
    {
        if (!empty($dateObject)) {
            $this->validateDateErrors();
            $dateString = $dateObject->format($format);
            return $dateString == $date;
        } else {
            return false;
        }
    }

    /**
     * Complement of validateDate() to generate Exceptions
     *
     * @return void
     */
    protected function validateDateErrors(): void
    {
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0) {
            foreach ($errors['errors'] as $key => $value) {
                throw new InvalidDateException($value);
            }
        }
    }


    /**
     * Return "min"
     *
     * @return string
     */
    public function getMin(): ?string
    {
        return $this->options['min'];
    }

    /**
     * @param mixed $option
     * 
     * @return void
     */
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
    public function getMax(): ?string
    {
        return $this->options['max'];
    }

    /**
     * @param mixed $option
     * 
     * @return void
     */
    public function setMax($option)
    {
        $this->options['max'] = $option;

        return $this;
    }

    /**
     * Return "format"
     *
     * @return string
     */
    public function getFormat(): ?string
    {
        return $this->options['format'];
    }

    /**
     * Return "inclusive"
     *
     * @return bool
     */
    public function getInclusive(): bool
    {
        return $this->options['inclusive'];
    }

    /**
     * @return mixed
     */
    public function getValueInput()
    {
        return $this->getValue();
    }
}
