<?php

namespace Commands;

use Helpers\Command;
use Helpers\CommandParser as Parser;

class UpdateCommand extends Command
{
    public function handle()
    {
        $row = [];
        $search = -1;
        do {
            $search = readline("Row Id:");
            $row = $this->table->getRowByID($search);
        } while (empty($row));

        $fillables = $this->table->getFillables();
        foreach ($this->table->getFields() as $field => $constraint) {
            if (in_array($field, $fillables)) {
                Parser::printMessage("Changing the fields for given row (id=$search)(press enter for passing without change or type to change)\n");
                Parser::printMessage($field."(".$row[$field]."):");
                $line = readline();
                if (!empty($line)) {
                    Parser::printMessage($row[$field]."-->");
                    $row[$field] = $line;
                    Parser::printMessage($line, 2);
                }
            }
        }
        $row['updated_at'] = time();
        $this->table->updateRow($search, $row);
        $this->saveTable();
    }
}
