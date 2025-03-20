<?php

namespace Lontra\Validator;

use BadFunctionCallException;
use BadMethodCallException;
use Lontra\Validator\Exception\ClassNotFoundException;
use ReflectionClass;

/***
 * This class has tools for using laminas-validator and lontra-validator validations
 * 
 * @license MIT https://opensource.org/licenses/MIT
 * @version 0.3.0
 */
class Validator
{
    /***
     * Validates data using passed validation
     * @param string $validatorName Validation class
     * @param mixed $value The value to validate
     * @param mixed $options Validation options
     */
    public static function isValid(string $validatorName, mixed $value, mixed $options = null)
    {

        $class = new ReflectionClass($validatorName);
        if (empty($options)) {
            $instance = $class->newInstance();
        } else {
            /** @var AbstractValidator */
            $instance = $class->newInstanceArgs(['options' => $options]);
        }
        return $instance->isValid($value);
    }

    public static function __callStatic($name, $arguments)
    {
        $prefixLength = strlen('validate');
        /** @var AbstractValidator */
        $object = null;
        if(substr($name, 0, $prefixLength) == 'validate'){
            $className = substr($name, $prefixLength, strlen($name));
            if(class_exists('Lontra\\Validator\\' . $className)){
                $className = 'Lontra\\Validator\\' . $className;
                $object = new $className($arguments[1]);
            }else if(class_exists('Laminas\\Validator\\' . $className)){
                $className = 'Lontra\\Validator\\' . $className;
                $object = new $className($arguments[1]);
            }

            if(!empty($object)){
                return $object->isValid($arguments[0]);
            }

            throw new ClassNotFoundException("Classe não encontrada: " . $className, 1);
        }
        throw new BadMethodCallException("The callback refers to an undefined method or if some arguments are missing", 1);
    }
}
