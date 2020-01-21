<?php

namespace CodexShaper\DBM\Database\Schema;

use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Doctrine\DBAL\Schema\TableDiff;

class UpdateManager
{
    protected $data;
    protected $newTable;
    protected $originalTable;

    /**
     * Update table.
     *
     * @param array $table
     *
     * @return void
     */
    public function update($table)
    {
        if (! is_array($table)) {
            $table = json_decode($table, true);
        }

        $tableName = $table['oldName'];

        if (! SchemaManager::getInstance()->tablesExist($tableName)) {
            throw SchemaException::tableDoesNotExist($table['oldName']);
        }

        $this->newTable = Table::prepareTable($table);
        $this->data = $table;
        $this->originalTable = static::listTableDetails($table['oldName']);

        $this->updateTable();
    }

    /**
     * Get all table details.
     *
     * @param string $tableName
     *
     * @return \CodexShaper\DBM\Database\Schema\Table
     */
    public static function listTableDetails($tableName)
    {
        $columns = SchemaManager::getInstance()->listTableColumns($tableName);

        $foreignKeys = [];
        if (SchemaManager::getInstance()->getDatabasePlatform()->supportsForeignKeyConstraints()) {
            $foreignKeys = SchemaManager::getInstance()->listTableForeignKeys($tableName);
        }

        $indexes = SchemaManager::getInstance()->listTableIndexes($tableName);

        return new DoctrineTable($tableName, $columns, $indexes, $foreignKeys, false, []);
    }

    /**
     * Update table.
     *
     * @return void
     */
    public function updateTable()
    {
        $newTableName = '';

        if ($this->newTable->getName() != $this->originalTable->getName()) {
            $newTableName = $this->newTable->getName();
            // Make sure the new name doesn't already exist
            if (SchemaManager::getInstance()->tablesExist($newTableName)) {
                throw SchemaException::tableAlreadyExists($newTableName);
            }
        }

        // Rename columns
        $this->renameColumns();

        $tableDiff = (new Comparator())->diffTable($this->originalTable, $this->newTable);

        // Add new table name to tableDiff
        if ($newTableName) {
            if (! $tableDiff) {
                $tableDiff = new TableDiff($this->data['oldName']);
                $tableDiff->fromTable = $this->originalTable;
            }

            $tableDiff->newName = $newTableName;
        }

        // Update the table
        if ($tableDiff) {
            SchemaManager::getInstance()->alterTable($tableDiff);
        }
    }

    /**
     * Rename columns.
     *
     * @return void
     */
    protected function renameColumns()
    {
        $renamedColumnsDiff = new TableDiff($this->data['oldName']);
        $renamedColumnsDiff->fromTable = $this->originalTable;

        $countRenamedColumns = 0;

        foreach ($this->data['columns'] as $column) {
            $oldName = $column['oldName'];
            if ($this->originalTable->hasColumn($oldName)) {
                $newName = $column['name'];
                if ($newName != $oldName) {
                    // Count total Renamed Columns
                    $countRenamedColumns++;
                    $renamedColumnsDiff->renamedColumns[$oldName] = $this->newTable->getColumn($newName);
                }
            }
        }

        if ($renamedColumnsDiff) {
            SchemaManager::getInstance()->alterTable($renamedColumnsDiff);
            // Refresh original table after renaming the columns
            $this->originalTable = SchemaManager::getInstance()->listTableDetails($this->data['oldName']);
        }
    }
}
