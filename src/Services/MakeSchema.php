<?php


namespace LaravelTableToMigrations\Services;


use LaravelTableToMigrations\Helper\InfoTables;

class MakeSchema implements \LaravelTableToMigrations\Contracts\ServiceContract
{
    public static function run(InfoTables $infoTables, ...$opt)
    {

        $down = "Schema::drop('{$opt[0]->table_name}');";
        $up = "Schema::create('{$opt[0]->table_name}', function(Blueprint $" . "table) {\n";
        $tableDescribes = $infoTables->getTableDescribes($opt[0]->table_name);
        $timestamps = '';
        array_map(function ($value) use (&$timestamps, &$up) {
            if ($value->INDEX_NAME == 'PRIMARY') {
                $up .= "$" . "table->id();\n";
            } elseif ($value->COLUMN_KEY == 'MUL') {
                if ($value->INDEX_NAME != null) {
                    $row = "$" . "table->foreignId('" . $value->COLUMN_NAME . "')->index('" . $value->INDEX_NAME . "')->constrained('" . $value->REFERENCED_TABLE_NAME . "');\n";
                } else {
                    $row = "$" . "table->foreignId('" . $value->COLUMN_NAME . "')->constrained('" . $value->REFERENCED_TABLE_NAME . "');\n";
                }
                $up .= $row;
            } elseif ($value->COLUMN_NAME == 'created_at' || $value->COLUMN_NAME == 'updated_at') {
                $timestamps = "$" . "table->timestamps();\n";
            } else {
                $method = "";
                $para = strpos($value->COLUMN_TYPE, '(');
                $type = $para > -1 ? substr($value->COLUMN_TYPE, 0, $para) : $value->COLUMN_TYPE;
                $numbers = "";
                $nullable = $value->IS_NULLABLE == "NO" ? "" : "->nullable()";
                $default = empty($value->COLUMN_DEFAULT) ? "" : "->default(\"{$value->COLUMN_DEFAULT}\")";
                $unsigned = strpos($value->COLUMN_TYPE, "unsigned") === false ? '' : '->unsigned()';
                $unique = $value->COLUMN_KEY == 'UNI' ? "->unique()" : "";
                $choices = '';
                $comment = empty($value->COLUMN_COMMENT) ? "" : "->comment(\"{$value->COLUMN_COMMENT}\")";
                switch ($type) {
                    case 'enum':
                        $method = 'enum';
                        $choices = preg_replace('/enum/', 'array', $value->COLUMN_TYPE);
                        $choices = ", $choices";
                        break;
                    case 'int' :
                        $method = 'unsignedInteger';
                        break;
                    case 'bigint' :
                        $method = 'bigInteger';
                        break;
                    case 'samllint' :
                        $method = 'smallInteger';
                        break;
                    case 'char' :
                    case 'varchar' :
                        $para = strpos($value->COLUMN_TYPE, '(');
                        $numbers = ", " . substr($value->COLUMN_TYPE, $para + 1, -1);
                        $method = 'string';
                        break;
                    case 'float' :
                        $method = 'float';
                        break;
                    case 'decimal' :
                        $para = strpos($value->COLUMN_TYPE, '(');
                        $numbers = ", " . substr($value->COLUMN_TYPE, $para + 1, -1);
                        $method = 'decimal';
                        break;
                    case 'smallint' :
                        $para = strpos($value->COLUMN_TYPE, '(');
                        $numbers = ", " . substr($value->COLUMN_TYPE, $para + 1, -1);
                        $method = 'smallInteger';
                        break;
                    case 'tinyint' :
                        if ($value->COLUMN_TYPE == 'tinyint(1)') {
                            $method = 'boolean';
                        } else {
                            $method = 'tinyInteger';
                        }
                        break;
                    case 'date':
                        $method = 'date';
                        break;
                    case 'timestamp' :
                        $method = 'timestamp';
                        break;
                    case 'datetime' :
                        $method = 'dateTime';
                        break;
                    case 'mediumtext' :
                        $method = 'mediumtext';
                        break;
                    case 'text' :
                        $method = 'text';
                        break;
                }
                if ($value->COLUMN_KEY == 'PRI') {
                    $method = 'id';
                }
                $up .= "$" . "table->{$method}('{$value->COLUMN_NAME}'{$choices}{$numbers}){$nullable}{$default}{$unsigned}{$unique}{$comment};\n";
            }
        }, $tableDescribes);
        $up .= $timestamps;
        $up .= " });\n\n";
        $infoTables::$schema = array(
            'up' => $up,
            'down' => $down
        );

    }
}
