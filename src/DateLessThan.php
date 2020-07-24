<?php

namespace Lontra\Validator;

use InvalidArgumentException;
use Laminas\Validator\AbstractValidator;
use Lontra\Validator\Exception\InvalidDateException;

/**
 * The DateLessThan class checks whether the value is less then "max" dates using "format" for formatting
 *
 * @license MIT https://opensource.org/licenses/MIT
 */
class DateLessThan extends AbstractValidator
{
    protected $options = [
        'inclusive' => true,
        'max'       => 'now',
        'format'    => 'Y-m-d',
    ];

    const NOT_GREATER       = 'notLess';
    const NOT_GREATER_INCLUSIVE = 'notLessInclusive';
    const IVALID_FORMAT     = 'invalidFormat';
    const IVALID_DATE       = 'invalidDate';

    protected $messageTemplates = [
        self::NOT_GREATER           => "The input is not less than '%max%'",
        self::NOT_GREATER_INCLUSIVE    => "The input is not less than(inclusive) '%max%'",
        self::IVALID_FORMAT         => "The date is not in '%format%' format",
        self::IVALID_DATE           => "The parsed date was invalid",
    ];

    protected $messageVariables = [
        'max' => ['options' => 'max'],
    ];


    /**
     * Create a new DateLessThan instance with options
     *
     * @param array $options Array options with "max", "format" and "inclusive" keys and values
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
        if ($this->getMax() == 'now') {
            $now = new \DateTime();
            $this->setMax($now->format($this->getFormat()));
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
            if ($date > $maxDate) {
                $this->error(self::NOT_GREATER_INCLUSIVE);
                return false;
            }
        } else {
            if ($date >= $maxDate) {
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
