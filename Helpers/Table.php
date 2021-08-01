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
        $this->row[$this->autoIncrement++] = $row;
    }
}
