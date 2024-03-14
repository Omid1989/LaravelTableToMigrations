<?php


namespace LaravelTableToMigrations\Helper;

use DB;
use Illuminate\Support\Str;


class InfoTables
{
    private static $database = "";
    public static $schema = array();
    public static $stub = "";
    private static $Options = array();
    private static $count = 0;

    public function __construct()
    {
        echo 'new class create' . PHP_EOL;
    }

    public function setDatabase($database)
    {
        self::$database = $database;
    }

    public function setOptions($Options)
    {
        self::$Options = $Options;
    }

    public function getTablesCount()
    {
        return self::$count;
    }

    public function getTables()
    {
        $tables = DB::select('SELECT table_name FROM information_schema.tables
                                WHERE Table_Type="' . "BASE TABLE" . '" and table_schema="' . self::$database . '"');
        self::$count = count($tables);
        return $tables;
    }

    public function getTableDescribes($table)
    {
        return DB::select("select
                               COLUMNS.COLUMN_NAME
                             , COLUMNS.ORDINAL_POSITION
                             , COLUMNS.COLUMN_DEFAULT
                             , COLUMNS.IS_NULLABLE
                             , COLUMNS.DATA_TYPE
                             , COLUMNS.CHARACTER_MAXIMUM_LENGTH
                             , COLUMNS.COLUMN_TYPE
                             , COLUMNS.COLUMN_KEY
                             , COLUMNS.EXTRA
                             , COLUMNS.COLUMN_COMMENT
                             , KEY_COLUMN_USAGE.COLUMN_NAME name
                             , KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME
                             , KEY_COLUMN_USAGE.REFERENCED_COLUMN_NAME
                             , STATISTICS.INDEX_NAME
                            from information_schema.COLUMNS COLUMNS
                                    left join information_schema.KEY_COLUMN_USAGE KEY_COLUMN_USAGE
                                          on COLUMNS.TABLE_NAME = KEY_COLUMN_USAGE.TABLE_NAME and
                                             COLUMNS.TABLE_SCHEMA = KEY_COLUMN_USAGE.CONSTRAINT_SCHEMA and
                                             COLUMNS.TABLE_SCHEMA = KEY_COLUMN_USAGE.REFERENCED_TABLE_SCHEMA
                            and KEY_COLUMN_USAGE.COLUMN_NAME=COLUMNS.COLUMN_NAME
                            left join INFORMATION_SCHEMA.STATISTICS STATISTICS on COLUMNS.COLUMN_NAME=STATISTICS.COLUMN_NAME
                                  and COLUMNS.TABLE_NAME=STATISTICS.TABLE_NAME  and STATISTICS.TABLE_NAME=COLUMNS.TABLE_NAME
                            where COLUMNS.TABLE_NAME = '" . $table . "'
                            and COLUMNS.TABLE_SCHEMA = '" . self::$database . "'    order by ORDINAL_POSITION ASC ;");

    }

}
