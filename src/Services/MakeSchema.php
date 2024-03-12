<?php


namespace LaravelTableToMigrations\Services;


use LaravelTableToMigrations\Helper\InfoTables;

class MakeSchema implements \LaravelTableToMigrations\Contracts\ServiceContract
{

    public static function run(InfoTables $infoTables, ...$opt)
    {

        $downStack[] = $opt[0]->table_name;
        $down = "Schema::drop('{$opt[0]->table_name}');";
        $up = "Schema::create('{$opt[0]->table_name}', function(Blueprint $" . "table) {\n";
        $tableDescribes = $infoTables->getTableDescribes($opt[0]->table_name);
        $PRIMARY = [];
        $MUL = [];
        $timestamps = [];
        $OTHER = [];
        array_map(function($value) use (&$PRIMARY, &$MUL,&$timestamps,&$OTHER) {
            if ($value->INDEX_NAME == 'PRIMARY') {
                $PRIMARY[] = $value;
            } elseif($value->COLUMN_KEY == 'MUL') {
                $MUL[] = $value;
            }elseif($value->COLUMN_NAME == 'created_at' || $value->COLUMN_NAME == 'updated_at') {
                $timestamps[] = $value;
            }else{
                $OTHER[]=$value;
            }
        }, $tableDescribes);





        foreach ($tableDescribes as $values) {
            $method = "";
            $para = strpos($values->Type, '(');
            $type = $para > -1 ? substr($values->Type, 0, $para) : $values->Type;
            $numbers = "";
            $nullable = $values->Null == "NO" ? "" : "->nullable()";
            $default = empty($values->Default) ? "" : "->default(\"{$values->Default}\")";
            $unsigned = strpos($values->Type, "unsigned") === false ? '' : '->unsigned()';
            $unique = $values->Key == 'UNI' ? "->unique()" : "";
            $choices = '';

            if ($values->Field=='id'){

                $method = "id";
                $para = strpos($values->Type, '(');
                $type = '$para > -1 ? substr($values->Type, 0, $para) : $values->Type';
                $numbers = "";
                $nullable = "";
                $default = "";
                $unsigned = "";
                $unique = "";
                $choices = '';
                $values->Field=null;

            }




            switch ($type) {
                case 'enum':
                    $method = 'enum';
                    $choices = preg_replace('/enum/', 'array', $values->Type);
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
                    $para = strpos($values->Type, '(');
                    $numbers = ", " . substr($values->Type, $para + 1, -1);
                    $method = 'string';
                    break;
                case 'float' :
                    $method = 'float';
                    break;
                case 'decimal' :
                    $para = strpos($values->Type, '(');
                    $numbers = ", " . substr($values->Type, $para + 1, -1);
                    $method = 'decimal';
                    break;
                 case 'smallint' :
                    $para = strpos($values->Type, '(');
                    $numbers = ", " . substr($values->Type, $para + 1, -1);
                    $method = 'smallInteger';
                    break;
                case 'tinyint' :
                    if ($values->Type == 'tinyint(1)') {
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
            if ($values->Key == 'PRI') {
                $method = 'id';
            }
            $up .= " $" . "table->{$method}('{$values->Field}'{$choices}{$numbers}){$nullable}{$default}{$unsigned}{$unique};\n";
        }

        $up .= " });\n\n";
        $infoTables::$schema[$opt[0]->table_name] = array(
            'up' => $up,
            'down' => $down
        );

        // add foreign constraints, if any
        $tableForeigns =$infoTables->getForeignTables();
        if (sizeof($tableForeigns) !== 0) {
            foreach ($tableForeigns as $key => $value) {
                $up = "Schema::table('{$value->TABLE_NAME}', function($" . "table) {\n";
                $foreign = $infoTables->getForeigns($value->TABLE_NAME);
                foreach ($foreign as $k => $v) {
                    $up .= " $" . "table->foreign('{$v->COLUMN_NAME}')->references('{$v->REFERENCED_COLUMN_NAME}')->on('{$v->REFERENCED_TABLE_NAME}');\n";
                }
                $up .= " });\n\n";
                $infoTables::$schema[$value->TABLE_NAME . '_foreign'] = array(
                    'up' => $up,
                    'down' => ( ! in_array($value->TABLE_NAME, $downStack) ) ? $down : "",
                );
            }
        }


        dd($infoTables::$schema[$opt[0]->table_name]);

    }
}
