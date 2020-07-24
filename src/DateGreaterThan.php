<?php

namespace Lontra\Validator;

use DateTime;
use InvalidArgumentException;
use Laminas\Validator\AbstractValidator;
use Lontra\Validator\Exception\InvalidDateException;

/**
 * The DateInGreaterThan class checks whether the value is greater then "min" dates using "format" for formatting
 *
 * @license MIT https://opensource.org/licenses/MIT
 */
class DateGreaterThan extends AbstractValidator
{
    protected $options = [
        'inclusive' => true,
        'min'       => null,
        'format'    => null,
    ];

    const NOT_GREATER       = 'notGreater';
    const NOT_GREATER_INCLUSIVE = 'notGreaterInclusive';
    const IVALID_FORMAT     = 'invalidFormat';
    const IVALID_DATE       = 'invalidDate';

    protected $messageTemplates = [
        self::NOT_GREATER           => "The input is not greater than '%min%'",
        self::NOT_GREATER_INCLUSIVE    => "The input is not greater than(inclusive) '%min%'",
        self::IVALID_FORMAT         => "The date is not in '%format%' format",
        self::IVALID_DATE           => "The parsed date was invalid",
    ];

    protected $messageVariables = [
        'min' => ['options' => 'min'],
    ];


    /**
     * Create a new DateInGreaterThan instance with options
     *
     * @param array $options Array options with "min", "format" and "inclusive" keys and values
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
     * Apply de validation
     * 
     * @param mixed $value
     * 
     * @return void
     */
    public function isValid($value)
    {
        $this->setValue($value);

        // setting "now" date from format
        if ($this->getMin() == 'now') {
            $now = new DateTime();
            $this->setMin($now->format($this->getFormat()));
        }

        $minDate = \DateTime::createFromFormat($this->getFormat(), $this->getMin());
        if (!$this->validateDate($this->getFormat(), $this->getMin(), $minDate)) {
            throw new InvalidDateException("Invalid: 'min'({$this->getMin()}) format is different from '{$this->getFormat()}' \"format\".");
        }

        $date = \DateTime::createFromFormat($this->getFormat(), $this->getValue());
        if (!$this->validateDate($this->getFormat(), $this->getValue(), $date) || \DateTime::getLastErrors()['warning_count'] > 0) {
            $this->error(self::IVALID_DATE);
            return false;
        }

        if ($this->getInclusive()) {
            if ($date < $minDate) {
                $this->error(self::NOT_GREATER_INCLUSIVE);
                return false;
            }
        } else {
            if ($date <= $minDate) {
                $this->error(self::NOT_GREATER);
                return false;
            }
        }
        return true;
    }

    /**
     * Validate a Date using the 'format'
     *
     * @param  string    $format
     * @param  string    $date
     * @param  \DateTime $dateObject
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
     * Return "format"
     *
     * @return string
     */
    public function getFormat(): ?string
    {
        return $this->options['format'];
    }

    /**
     * @param mixed $option
     * 
     * @return void
     */
    public function setFormat($option)
    {
        $this->options['format'] = $option;

        return $this;
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
     * @param mixed $option
     * 
     * @return void
     */
    public function setInclusive($option)
    {
        $this->options['inclusive'] = $option;

        return $this;
    }
}
