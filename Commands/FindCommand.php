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
            $target[$clause[0]] = $clause[1];
        }
        dump($this->table->findRow($target));
        dd($target);
    }
}
