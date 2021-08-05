<?php

namespace Commands;

use Helpers\Command;
use Helpers\CommandParser as Parser;

class CreateCommand extends Command
{
    public function handle()
    {
        dump($this->table);

        $row = array();
        $fillables = $this->table->getFillables();
        foreach ($this->table->getFields() as $field => $constraint) {
            if (in_array($field, $fillables)) {
                $constResult = false;
                do {
                    Parser::printMessage($field.': ');
                    $line = readline();
                    if (empty($line)) {
                        if (($row[$field] = $this->table->fetchDefault($field)) == null) {
                            Parser::printMessage("$field is required.\n", 2);
                            continue;
                        }
                    }
                    if (($constResult = $this->table->checkConstraint($line, $field)) == false) {
                        Parser::printMessage("The input doesn't checks out the constraints\n");
                    }
                    $row[$field] = $line;
                } while (!$constResult);
            }
        }
        /* $row['created_at'] = time(); */
        $row['created_at'] = parent::$defaults['created_at']();
        $this->table->addRow($row);
        $this->saveTable(); //save table to disk

        dd($row);
    }
}
