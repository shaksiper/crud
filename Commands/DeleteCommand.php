<?php

namespace Commands;

use Helpers\Command;
use Helpers\CommandParser as Parser;

class DeleteCommand extends Command
{
    public function handle()
    {
        # code...
        /* dump($this->table); */

        $target = [];
        foreach (explode(' ', $this->arguments['pk']) as $condition) {
            $clause = explode(':', $condition);
            if (count($clause)%2 != 0) { // makes sure that each clause has field and value
                continue;
            }
            $target[$clause[0]] = $clause[1];
        }
        $resultId = $this->table->findRow($target);
        $result = $this->table->getRowByID($resultId);

        dump($result);
        if ($resultId > 0) {
            Parser::printMessage("Are you sure to delete the row? [Yes/No]", 2);
            if (in_array(strtolower(readline()), array("yes", "y"))) {
                $this->table->deleteRow($resultId);
            }
        }
        $this->saveTable(); //save table to disk
    }
}
