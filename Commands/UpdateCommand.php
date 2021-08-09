<?php

namespace Commands;

use Helpers\Command;
use Helpers\CommandParser as Parser;
use Helpers\Request;

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

        Parser::printMessage("Changing the fields for given row (id=$search)(press enter for passing without change or type to change)\n");
        $fillables = $this->table->getFillables();
        $changeFlag = false;
        // Getting each constraint for the fields whenever applicable
        foreach ($this->table->getFields() as $field => $constraint) {
            if (in_array($field, $fillables)) {
                Parser::printMessage($field."($row[$field]):");
                do {
                    $line = readline();
                    $request = new Request($line, $field);
                    if (($constResult = !in_array(false, $request->validate($constraint))) == false) {
                        Parser::printMessage("The input doesn't checks out the constraints\n", 3);
                    }
                } while (!$constResult);
                if (!empty($line)) {
                    $changeFlag = true;
                    Parser::printMessage($row[$field]."-->");
                    $row[$field] = $line;
                    Parser::printMessage($line, 2);
                }
            }
        }
        if ($changeFlag) {
            $row['updated_at'] = time();
            $this->table->updateRow($search, $row);
            $this->saveTable();
        } else {
            Parser::printMessage("No change submitted.", 2);
        }
    }
}
