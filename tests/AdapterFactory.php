<?php

namespace Lontra\ValidatorTest;

use Laminas\Db\Metadata\Metadata;
use Laminas\Db\Metadata\Source\Factory;
use Laminas\Db\Sql\Ddl\AlterTable;
use Laminas\Db\Sql\Ddl\Column\Column;
use Laminas\Db\Sql\Ddl\CreateTable;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Sql;

class AdapterFactory
{
    /**
     * @return \Laminas\Db\Adapter\Adapter
     */
    public static function create()
    {
        $adapter = new \Laminas\Db\Adapter\Adapter([
            'driver'   => 'Pdo_Sqlite',
            'database' => 'integration_tests.db',
        ]);

        $metadata = Factory::createSourceFromAdapter($adapter);
        $tables = $metadata->getTableNames();
        //debug
        // fwrite(STDERR, print_r($tables, TRUE));

        //Create table to integration test
        if (array_search('test_table', $tables, false) === false) {
            $table = new CreateTable('test_table');
            $table->addColumn(new Column('id'));
            $sql = new Sql($adapter);
            $adapter->query(
                $sql->buildSqlString($table),
                $adapter::QUERY_MODE_EXECUTE
            );
            //Insert values to integration tests
            for ($i = 0; $i < 10; $i++) {
                $insert = new Insert('test_table');
                $insert->columns(['id']);
                $insert->values(['id' => $i], $insert::VALUES_MERGE);
                $sql->prepareStatementForSqlObject($insert)->execute();
            }
        }

        return $adapter;
    }
}
