<?php

namespace Commands;

use Helpers\Command;
use Helpers\CommandParser as Parser;

class DeleteCommand extends Command
{
    public function handle()
    {
        # code...
        dump($this->table);

        $target = [];
        foreach (explode(' ', $this->arguments['pk']) as $condition) {
            $clause = explode(':', $condition);
            if (count($clause)%2 != 0) { // makes sure that each clause has field and value
                continue;
            }
            $target[$clause[0]] = $clause[1];
        }
        $result = $this->table->getRowByID($this->table->findRow($target));

        if (!empty($result)) {
            Parser::printMessage("Are you sure to delete the row? [Yes/No]");
            if (in_array(strtolower(readline()), array("yes", "y"))) {
                $this->table->deleteRow($this->table->findRow($target));
            }
        }
        $this->saveTable(); //save table to disk
    }
}
