<?php

namespace Lontra\Validator\Db;

use InvalidArgumentException;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Validator\AbstractValidator;
use RuntimeException;

/**
 * The ArrayRecordExists check if value in array exists
 *
 * @author cledilsonweb
 * @license MIT https://opensource.org/licenses/MIT
 */
class ArrayRecordExists extends AbstractValidator
{
    protected $options = [
        'adapter' => null,
        'table'    => null,
        'field'     => null,
    ];

    public const ERROR_NO_ARRAY_RECORD_FOUND = 'noRecordFound';
    public const ERROR_SOME_NO_ARRAY_RECORD_FOUND = 'someNoRecordFound';

    protected $messageTemplates = [
        self::ERROR_NO_ARRAY_RECORD_FOUND => 'No record matching the input was found',
        self::ERROR_SOME_NO_ARRAY_RECORD_FOUND => 'Some items were not found',
    ];

    /**
     * Create a new ArrayRecordExists instance with options
     *
     * @param array $options Array options with [adapter(\Laminas\Db\Adapter\Adapter), table, field] keys and values
     * 
     * @return void
     */
    public function __construct(array $options = null)
    {
        if (!is_array($options)) {
            throw new InvalidArgumentException("Invalid argument: Parameter passed is not an array.");
        }

        if (!array_key_exists('field', $options)) {
            throw new InvalidArgumentException("Invalid argument: Field option missing.");
        }

        if (!array_key_exists('table', $options)) {
            throw new InvalidArgumentException("Invalid argument: Table option missing.");
        }

        if (!array_key_exists('adapter', $options)) {
            throw new InvalidArgumentException("Invalid argument: Adapter option missing.");
        }

        parent::__construct($options);
    }

    /**
     * @param array $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_array($value)) {
            throw new RuntimeException('Runtime Error: Value is not array');
        }

        $this->setValue($value);

        $result = $this->executeSelect($value);
        $notFound = $this->compareResults($result);

        if($notFound == 0){
            return true;
        }

        if ($notFound == count($this->getValue())) {
            $this->error(self::ERROR_NO_ARRAY_RECORD_FOUND);
            return false;
        }

        if ($notFound < count($this->getValue())) {
            $this->error(self::ERROR_SOME_NO_ARRAY_RECORD_FOUND);
            return false;
        }

        return true;
    }

    /**
     * Create de select query with values from array
     * @param array $value
     * @return Select
     */
    private function getSelect($value)
    {
        $select = new Select($this->getTable());
        $select->columns([$this->getField()]);
        $select->where([
            $this->getField() => $value
        ]);

        $this->select = $select;

        return $this->select;
    }

    /**
     * Execute the generated query
     * @param array $value
     * 
     * @return ResultInterface
     */
    private function executeSelect($value){
        $select = $this->getSelect($value);
        $sql = new Sql($this->getAdapter());
        $statemant = $sql->prepareStatementForSqlObject($select);
        return $statemant->execute();
    }

    /**
     * Compare results of query and value
     * @param mixed $result
     * 
     * @return int Total not found itens
     */
    private function compareResults($result){
        $valuesFromDb = [];
        foreach ($result as $row) {
            $valuesFromDb[] = $row[$this->getField()];
        }
        return count(array_diff($this->getValue(), $valuesFromDb));
    }

    /**
     * Get Adapter
     *
     * @return Adapter
     */
    public function getAdapter(){
        return $this->options['adapter'];
    }

    /**
     * @param Adapter $adapter
     * 
     * @return self
     */
    public function setAdapter($adapter){
        $this->options['adapter'] = $adapter;
        return $this;
    }

    /**
     * @return string
     */
    public function getTable(){
        return $this->options['table'];
    }

    /**
     * @param string $table
     * 
     * @return self
     */
    public function setTable($table)
    {
        $this->options['table'] = $table;
        return $this;
    }

    /**
     * @return string
     */
    public function getField(){
        return $this->options['field'];
    }

    /**
     * @param string $field
     * 
     * @return self
     */
    public function setField($field)
    {
        $this->options['field'] = $field;
        return $this;
    }
}
