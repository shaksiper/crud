<?php

namespace Helpers;

class Table
{
    protected $fields;
    protected $fillable;

    protected $autoIncrement = 1;

    protected $rows = [];

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


    /*
    *
    *Returns the index (ID) of the row if found
        Otherwise returns -1 for not found
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
}
