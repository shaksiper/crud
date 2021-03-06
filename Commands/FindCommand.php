<?php

namespace Commands;

use Helpers\Command;

class FindCommand extends Command
{
    public function handle()
    {
        dump($this->table);

        $target = [];
        foreach (explode(' ', $this->arguments['condition']) as $condition) {
            $clause = explode(':', $condition);
            if (count($clause)%2 != 0) { // makes sure that each clause has field and value
                continue;
            }
            $target[$clause[0]] = $clause[1];
        }
        if (!empty($target)) { // can't look for an empty target
            dd($this->table->getRowByID($this->table->findRow($target)));
        }
    }
}
