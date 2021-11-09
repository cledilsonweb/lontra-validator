<?php

namespace Lontra\Validator;

use InvalidArgumentException;
use Laminas\Validator\AbstractValidator;

/**
 * The Password class checks if the value is in the specified pattern
 *
 * @license MIT https://opensource.org/licenses/MIT
 */
class Password extends AbstractValidator
{
    protected $options = [
        'uppercase' => true,
        'lowercase' => true,
        'number'    => true,
        'specialChars'    => true,
    ];

    // All rules 
    protected $all = false;

    const HAS_NO_UPPERCASE      = 'hasNoUppercase';
    const HAS_NO_LOWERCASE      = 'hasNoLowercase';
    const HAS_NO_NUMBER         = 'hasNoNumber';
    const HAS_NO_SPECIALCHARS   = 'hasNoSpecialchars';
    const HAS_NO_ALL   = 'hasNoAll';

    protected $messageTemplates = [
        self::HAS_NO_UPPERCASE      => "Password has no upper case letter",
        self::HAS_NO_LOWERCASE      => "Password has no lower case letter",
        self::HAS_NO_NUMBER         => "Password has no number",
        self::HAS_NO_SPECIALCHARS   => "Password has no special character",
        self::HAS_NO_ALL   => "Password must have upper and lower case letter, number and special character",
    ];


    /**
     * Create a new Password instance with options
     *
     * @param array $options Array options  keys and values
     * 
     * @return void
     */
    public function __construct(array $options = [])
    {
        if (!is_array($options)) {
            throw new InvalidArgumentException("Invalid argument: Parameter passed is not an array.");
        }

        if (empty($options)) {
            $this->all = true; // If empty, all rules
        } else {
            if (!in_array(false, $options)) {
                $this->all = true;
            }
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

        $upper = preg_match('@[A-Z]@', $this->getValue());
        $lower = preg_match('@[a-z]@', $this->getValue());
        $number = preg_match('@[0-9]@', $this->getValue());
        $special = preg_match('@[^\w]@', $this->getValue());

        $valid = true;

        // All errors, same message
        if ($this->all && !$upper && !$lower && !$number && !$special) {
            $this->error($this->error(self::HAS_NO_ALL));
            return false;
        }

        if (!$upper && $this->getUppercase()) {
            $this->error(self::HAS_NO_UPPERCASE);
            $valid = false;
        }

        if (!$lower && $this->getLowercase()) {
            $this->error(self::HAS_NO_LOWERCASE);
            $valid = false;
        }

        if (!$number && $this->getNumber()) {
            $this->error(self::HAS_NO_NUMBER);
            $valid = false;
        }

        if (!$special && $this->getSpecialChars()) {
            $this->error(self::HAS_NO_SPECIALCHARS);
            $valid = false;
        }

        return $valid;
    }


    /**
     * Get the value of options
     *
     * @return bool
     */
    public function getUppercase(): bool
    {
        return $this->options['uppercase'];
    }

    /**
     * Set the value of options
     * 
     * @param mixed $option
     *
     * @return self
     */
    public function setUppercase($option)
    {
        $this->options['uppercase'] = $option;

        return $this;
    }

    /**
     * Get the value of options
     *
     * @return bool
     */
    public function getLowercase(): bool
    {
        return $this->options['lowercase'];
    }

    /**
     * Set the value of options
     *
     * @param mixed $option
     *
     * @return self
     */
    public function setLowercase($option)
    {
        $this->options['lowercase'] = $option;

        return $this;
    }

    /**
     * Get the value of options
     *
     * @return bool
     */
    public function getNumber(): bool
    {
        return $this->options['number'];
    }

    /**
     * Set the value of options
     *
     * @param mixed $option
     *
     * @return self
     */
    public function setNumber($option)
    {
        $this->options['number'] = $option;

        return $this;
    }

    /**
     * Get the value of options
     *
     * @return bool
     */
    public function getSpecialChars(): bool
    {
        return $this->options['specialChars'];
    }

    /**
     * Set the value of options
     *
     * @param mixed $option
     *
     * @return self
     */
    public function setSpecialChars($option)
    {
        $this->options['specialChars'] = $option;

        return $this;
    }
}
