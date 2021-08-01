<?php

namespace Helpers;

class Command
{
    protected Table $table;
    protected $arguments = [];

    public function setTable($table)
    {
        $onStorage = __DIR__ . '/../Storage/' . sha1($table);

        if (file_exists($onStorage)) {
            dump('from_stroage');
            $this->table = unserialize(file_get_contents($onStorage));
        } else {
            dump('from_class');
            $this->table = new $table;
        }
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    protected function saveTable()
    {
        $onStorage = __DIR__ . '/../Storage/' . sha1(get_class($this->table));
        $serialTable = serialize($this->table);
        file_put_contents($onStorage, $serialTable);
        dd($onStorage);
    }

    public function handle()
    {
        return;
    }
}
