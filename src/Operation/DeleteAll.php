<?php
/*
 * This file is part of DBUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\DbUnit\Operation;

use PDOException;
use PHPUnit\DbUnit\Database\IConnection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\DataSet\ITable;
use PHPUnit_Extensions_Database_Operation_Exception;
use PHPUnit_Extensions_Database_Operation_IDatabaseOperation;

/**
 * Deletes all rows from all tables in a dataset.
 */
class DeleteAll implements PHPUnit_Extensions_Database_Operation_IDatabaseOperation
{
    public function execute(IConnection $connection, IDataSet $dataSet)
    {
        foreach ($dataSet->getReverseIterator() as $table) {
            /* @var $table ITable */

            $query = "
                DELETE FROM {$connection->quoteSchemaObject($table->getTableMetaData()->getTableName())}
            ";

            try {
                $connection->getConnection()->query($query);
            } catch (PDOException $e) {
                throw new PHPUnit_Extensions_Database_Operation_Exception('DELETE_ALL', $query, [], $table, $e->getMessage());
            }
        }
    }
}
