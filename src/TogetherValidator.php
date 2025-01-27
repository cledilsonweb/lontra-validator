<?php

namespace Lontra\Validator;

use Laminas\Stdlib\ArrayUtils;
use Laminas\Validator\AbstractValidator;
use Traversable;

/**
 * The Together Validator class validates fields together. The fields must be filled in together or both fields must be empty. 
 * The names of the other fields must be in the "inputs" key, an array with names.
 * 
 * @license MIT https://opensource.org/licenses/MIT
 */
class TogetherValidator extends AbstractValidator
{
    const NOT_TOGETHER = 'invalid';

    protected $inputs = [];
    protected $inputsString = '';

    protected $messageTemplates = [
        self::NOT_TOGETHER => 'This field will be completed together with these: %inputs%.'
    ];

    protected $messageVariables = [
        'inputs' => 'inputsString'
    ];

    /**
     * Sets validator options
     *
     * @param  mixed $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (is_array($options) && array_key_exists('inputs', $options)) {
            $this->setInputs($options['inputs']);
        } elseif (null !== $options) {
            $this->setInputs($options);
        }

        parent::__construct(is_array($options) ? $options : null);
    }

    public function isValid($value, $context = null)
    {
        $this->setValue($value);

        if (is_array($this->getInputs())) {
            foreach ($this->getInputs() as $input) {
                if ($this->notTogether($context[$input], $value)) {
                    $this->error(self::NOT_TOGETHER);
                    return false;
                }
            }
        } else {
            if ($this->notTogether($context[$this->getInputs()], $value)) {
                $this->error(self::NOT_TOGETHER);
                return false;
            }
        }

        return true;
    }

    private function notTogether($inputValue, $value)
    {
        return !((empty($inputValue) && empty($value)) || (!empty($inputValue) && !empty($value)));
    }

    public function setInputs($inputs)
    {
        $this->inputs = (is_array($inputs) && $this->isAssociative($inputs)) ? array_keys($inputs) : $inputs;
        if (is_array($inputs)) {
            $this->inputsString = implode(', ', $inputs);
        }
        return $this;
    }

    public function getInputs()
    {
        return $this->inputs;
    }

    private function isAssociative($array): bool
    {
        $c = count($array);
        for ($i = 0; $i < $c; $i++) {
            if (!array_key_exists($i, $array)) {
                return true;
            }
        }
        return false;
    }
}
