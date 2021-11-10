<?php

use Lontra\Validator\Db\ArrayRecordExists;
use Lontra\ValidatorTest\AdapterFactory;
use Lontra\ValidatorTest\DbAdapter;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayRecordExistsTest
 * @author cledilsonweb
 */
class ArrayRecordExistsTest extends TestCase
{

    /**
     * Basic use of ArrayRecordExists
     *
     * @return void
     */
    public function testArrayRecordExistsOptions()
    {
        $adapter = AdapterFactory::create();
        $options = [
            'table' => 'tableTest',
            'field' => 'fieldTest',
            'adapter' => $adapter
        ];
        $validator = new ArrayRecordExists($options);

        $optionsValidator = [
            'table' => $validator->getTable(),
            'field' => $validator->getField(),
            'adapter' => $validator->getAdapter(),
        ];

        $this->assertSame($options, $optionsValidator);
    }

    /**
     * @return void
     */
    public function testArrayRecordExistsParamsOptions()
    {
        $validator = new ArrayRecordExists([
            'table' => 'test_table',
            'field' => 'id',
            'adapter' => AdapterFactory::create()
        ]);

        $this->assertTrue($validator->isValid([]), implode(' | ', $validator->getMessages()));
        $this->assertTrue($validator->isValid(['1', '2', '3']), implode(' | ', $validator->getMessages()));
        $this->assertTrue($validator->isValid(['5']), implode(' | ', $validator->getMessages()));
        $this->assertFalse($validator->isValid(['A', 'B', 'C']), implode(' | ', $validator->getMessages()));
        $this->assertFalse($validator->isValid(['1', '2', 'C']), implode(' | ', $validator->getMessages()));
        $this->assertFalse($validator->isValid(['10', '2', '3']), implode(' | ', $validator->getMessages()));
    }

    /**
     * Basic use of ArrayRecordExists
     *
     * @return void
     */
    public function testArrayRecordExistsNoParams()
    {
        $this->expectException(InvalidArgumentException::class);

        $validator = new ArrayRecordExists();

        $this->expectException(RuntimeException::class);

        $validator->isValid('Is not an array');
    }
}
