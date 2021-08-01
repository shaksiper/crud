<?php

namespace Commands;

use Helpers\Command;

class CreateCommand extends Command
{
    public function handle()
    {
        dump($this->table);

        $row = array();
        $fillables = $this->table->getFillables();
        foreach ($this->table->getFields() as $field => $constraint) {
            $row[$field] = (in_array($field, $fillables) ? readline($field . ': ') 
                : ($field == 'created_at' ? time() 
                : ''));
        }
        $this->table->addRow($row);

        $this->saveTable();
        
        dd($row);

    }

    private function checkConstraint($field, $input)
    {

        $constraint = $this->table->getFields()[$field];
        $constraint = explode(':', $constraint);

        return true;
    }
}
