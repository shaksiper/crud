<?php

namespace Helpers;

class Table
{
    protected $fields;
    protected $fillable;

    protected $autoIncrement = 1;

    protected $rows = [];

    /*
    * The arrays of constraints. They include which function corresponds to each constraint
    * */
    protected static $unitConstraints = [
        'string' => 'isString',
        'integer' => 'isInteger',
        ];
    protected static $singleConstraints = [
        'unique' => 'isUnique',
    ];
    protected static $defaults = [
        'current_timestamp' => 'time',
        /* 'null' => function() {return null;}, */
    ];

    /**
     * Get fillable fields
     *
     * @return array
     */
    public function getFillables()
    {
        return $this->fillable;
    }

    /**
     * Get fields
     *
     * @return array
     * */
    public function getFields()
    {
        return $this->fields;
    }

    public function addRow($row)
    {
        $this->rows[$this->autoIncrement++] = $row;
    }

    /**
    * Returns the index (ID) of the row if found
    *    Otherwise returns -1 for not found
    *
    * @return int
    *
    * */
    public function findRow($target)
    {
        foreach ($this->rows as $key => $row) {
            if (($target == array_intersect_assoc($target, $row))) {
                dump(array_intersect_assoc($target, $row));
                return $key;
            }
        }
        return -1;
    }

    public function getRowByID($id)
    {
        return $this->rows[$id] ?? -1;
    }

    public function deleteRow($rowId)
    {
        if (isset($this->rows[$rowId])) {
            unset($this->rows[$rowId]);
        }
    }

    public function updateRow($rowId, $newRow)
    {
        if (isset($this->rows[$rowId])) {
            $this->rows[$rowId] = $newRow;
        }
    }

    /*
    *
    *Checks given constaints for the field
    *
    * @return true if all constrints passes
    *
    * */
    public function checkConstraint($input, $type): bool
    {
        foreach ($this->getFields()[$type] as $constraint) {
            dump($constraint);
            $pair = explode(':', $constraint, 2);
            if (array_key_exists($pair[0], self::$unitConstraints)) {
                $method = self::$unitConstraints[$pair[0]];
                if (!$this->{$method}($input, $pair[1])) {
                    return false;
                }
            } elseif (array_key_exists($pair[0], self::$singleConstraints)) {
                $method = self::$singleConstraints[$pair[0]];
                if (!$this->{$method}($input, $type)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function fetchDefault($field)
    {
        if (array_key_exists($field, self::$defaults)) {
            return self::$defaults[$field]();
        } else {
            return null;
        }
    }

    /*
    * @return true if $target is string in lenght of $size
    *
    * */
    private function isString($target, $len)
    {
        return strlen($target) <= $len;
    }

    /*
    *@return true if $target is int
    * */
    private function isInteger($target)
    {
        return is_int($target);
    }

    /**
    *
    *Checks whether the input value is unique
    *
    * @param string $target is looked in $targetColumn columns of rows
    *
    */
    private function isUnique($target, $targetColumn)
    {
        $columns = array_column($this->rows, $targetColumn);
        return !in_array($target, $columns);
    }
}
