<?php

namespace CodexShaper\DBM\Database\Schema;

use CodexShaper\DBM\Database\Types\Type;
use CodexShaper\DBM\Facades\Driver;
use CodexShaper\DBM\Facades\MongoDB;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Table
{
    /**
     * Returns a list of all tables in the current database.
     *
     * @return array|string[]
     */
    public static function all()
    {
        // MongoDb
        if (Driver::isMongoDB()) {
            return MongoDB::getCollectionNames();
        }

        return SchemaManager::getInstance()->listTableNames();
    }

    /**
     * Get table details.
     *
     * @param string $tableName
     *
     * @return array
     */
    public static function getTable($tableName)
    {
        if (Driver::isMongoDB()) {
            return MongoDB::getCollection($tableName);
        }

        Type::registerCustomTypes();

        $table = SchemaManager::getInstance()->listTableDetails($tableName);
        $primaryKeyName = $table->getPrimaryKey();
        $options = $table->getOptions();

        return [
            'name' => $table->getName(),
            'oldName' => $table->getName(),
            'columns' => static::getColumns($table),
            'indexes' => static::getIndexes($table),
            'foreignKeys' => static::getForeignKeys($table),
            'primaryKeyName' => $primaryKeyName,
            'options' => $options,
        ];
    }

    /**
     * Get column names.
     *
     * @param string $tableName
     *
     * @return array
     */
    public static function getColumnsName($tableName)
    {
        $columns = static::getTable($tableName)['columns'];

        $columnsName = [];

        foreach ($columns as $column) {
            $columnsName[] = $column->name;
        }

        return $columnsName;
    }

    /**
     * Create new table.
     *
     * @param array $table
     *
     * @return array|object|void
     */
    public static function create($table)
    {
        if (! is_array($table)) {
            $table = json_decode($table, true);
        }

        // MongoDB
        if (Driver::isMongoDB()) {
            return MongoDB::createCollection($table['name']);
        }

        $newTable = self::prepareTable($table);

        $schema = SchemaManager::getInstance();
        $schema->createTable($newTable);
    }

    /**
     * Update table.
     *
     * @param array $table
     *
     * @return true|void
     */
    public static function update($table)
    {
        if (! is_array($table)) {
            $table = json_decode($table, true);
        }

        if (Driver::isMongoDB()) {
            return MongoDB::updateCollection($table);
        }

        (new UpdateManager())->update($table);
    }

    /**
     * Drop table.
     *
     * @param string $tableName
     *
     * @return void
     */
    public static function drop($tableName)
    {
        if (Driver::isMongoDB()) {
            return MongoDB::dropCollection($tableName);
        }

        return SchemaManager::getInstance()->dropTable($tableName);
    }

    /**
     * Prepare table.
     *
     * @param array $table
     *
     * @return \Doctrine\DBAL\Schema\Table
     */
    public static function prepareTable($table)
    {
        if (! is_array($table)) {
            $table = json_decode($table, true);
        }

        Type::registerCustomTypes();

        $conn = 'database.connections.'.config('database.default');

        $table['options']['collate'] = $table['options']['collation'] ?? config($conn.'.collation', 'utf8mb4_unicode_ci');
        if (Driver::isMysql()) {
            $table['options']['charset'] = $table['options']['charset'] ?? config($conn.'.charset', 'utf8mb4');
        }

        $tableName = $table['name'];
        $columns = $table['columns'];
        $indexes = $table['indexes'];
        $foreignKeys = $table['foreignKeys'];

        // Make Doctrain  columns
        $DoctrineColumns = [];
        foreach ($columns as $column) {
            $DoctrineColumn = Column::create($column);
            $DoctrineColumns[$DoctrineColumn->getName()] = $DoctrineColumn;
        }

        // Make Doctrain indexes
        $DoctrineIndexes = [];
        foreach ($indexes as $index) {
            $DoctrineIndex = Index::create($index);
            $DoctrineIndexes[$DoctrineIndex->getName()] = $DoctrineIndex;
        }

        // Make Doctrain Foreign Keys
        $DoctrineForeignKeys = [];
        foreach ($foreignKeys as $foreignKey) {
            $DoctrineForeignKey = ForeignKey::create($foreignKey);

            $DoctrineForeignKeys[$DoctrineForeignKey->getName()] = $DoctrineForeignKey;
        }

        $options = $table['options'];

        return new DoctrineTable($tableName, $DoctrineColumns, $DoctrineIndexes, $DoctrineForeignKeys, false, $options);
    }

    /**
     * Get all columns.
     *
     * @return array
     */
    public static function getColumns(DoctrineTable $table)
    {
        $columns = [];

        $order = 1;
        foreach ($table->getColumns() as $column) {
            $columns[] = (object) array_merge(
                Column::toArray($column),
                ['order' => $order]
            );
            $order++;
        }

        return $columns;
    }

    /**
     * Get all indexes.
     *
     * @return array
     */
    public static function getIndexes(DoctrineTable $table)
    {
        $indexes = [];

        foreach ($table->getIndexes() as $index) {
            $indexes[] = (object) array_merge(
                Index::toArray($index),
                ['table' => $table->getName()]
            );
        }

        return $indexes;
    }

    /**
     * Get all foreign keys.
     *
     * @return array
     */
    public static function getForeignKeys(DoctrineTable $table)
    {
        $foreignKeys = [];

        foreach ($table->getForeignKeys() as $name => $foreignKey) {
            $foreignKeys[$name] = ForeignKey::toArray($foreignKey);
        }

        return $foreignKeys;
    }

    /**
     * Check table exists or not.
     *
     * @param string $tableName
     *
     * @return bool
     */
    public static function exists($tableName)
    {
        if (Driver::isMongoDB()) {
            return MongoDB::hasCollection($tableName);
        }

        if (! SchemaManager::getInstance()->tablesExist($tableName)) {
            throw SchemaException::tableDoesNotExist($tableName);
        }

        return true;
    }

    /**
     * Get tables with pagination.
     *
     * @param int $perPage
     * @param int|null $page
     * @param array $options
     * @param string $query
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate($perPage = 15, $page = null, $options = [], $query = '')
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $options['path'] = Paginator::resolveCurrentPath();
        $items = static::all();
        $collection = $items instanceof Collection ? $items : Collection::make($items);
        if (! empty($query)) {
            $collection = $collection->filter(function ($value, $key) use ($query) {
                return false !== stristr($value, $query);
            });
        }

        return new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage, $page, $options);
    }
}
