<?php

namespace Helpers;

class CommandParser
{
    protected $method;
    protected $table;
    protected $arguments;

    // acceptable tables
    protected $tables = [];

    // acceptable methods
    protected $methods = [
        'create' => \Commands\CreateCommand::class,
        'find' => \Commands\FindCommand::class,
        'update' => \Commands\UpdateCommand::class,
        'delete' => \Commands\DeleteCommand::class,
    ];

    public function __construct()
    {
        $inputs = $_SERVER['argv'];

        ////////////////////////////////////////////////////////

        $this->tables = $this->getTables();

        ////////////////////////////////////////////////////////

        // help output
        if (count($inputs) === 1) {
            $this->printCommandList();
        }

        if ($inputs[1] === 'list') {
            dd($this->tables);
        }

        ////////////////////////////////////////////////////////

        // parsed method and table
        $methodAndTable = explode(':', $inputs[1], 2);

        ////////////////////////////////////////////////////////

        // if method is not acceptable
        if (!isset($this->methods[$methodAndTable[0]])) {
            $this->error('Current method is not supported. Please run "php crud" for help.');
        }

        ////////////////////////////////////////////////////////

        // if table name is missing
        if (!isset($methodAndTable[1]) || empty($methodAndTable[1])) {
            $this->error('Table name is required after the methos. Please run "php crud" for help.');
        }

        ////////////////////////////////////////////////////////

        $this->method = $methodAndTable[0];

        ////////////////////////////////////////////////////////

        if (!isset($this->tables[$methodAndTable[1]])) {
            $this->error('Table not found. Please run "php crud:list" for help.');
        }

        ////////////////////////////////////////////////////////

        $this->table = $methodAndTable[1];

        ////////////////////////////////////////////////////////

        $arguments = [];

        switch ($this->method) {
            case 'update':
            break;
            case 'delete':
                $arguments['pk'] = $inputs[2];

                //
                break;

            case 'find':
                unset($inputs[0]);
                unset($inputs[1]);

                $arguments['condition'] = implode(' ', $inputs);

                //
                break;
        }

        $this->arguments = $arguments;

        ////////////////////////////////////////////////////////
    }

    /**
     * Run the current command's handle method.
     *
     * @return mixed
     */
    public function run()
    {
        /**
         * @var \Helpers\Command
         */
        $commandObject = new $this->methods[$this->method]();

        $commandObject->setTable('Tables\\' . $this->tables[$this->table]);
        $commandObject->setArguments($this->arguments);

        try {
            dd($commandObject->handle());

            //
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    private function getTables()
    {
        $files = scandir(__DIR__ . '/../Tables', SCANDIR_SORT_ASCENDING);
        $tableFiles = [];

        foreach ($files as $file) {
            if (substr($file, -4) == '.php') {
                $tableFiles[] = substr($file, 0, -4);
            }
        }

        $tables = [];

        foreach ($tableFiles as $tableFile) {
            // https://stackoverflow.com/a/35719689
            $tableName = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $tableFile));

            $tables[$tableName] = $tableFile;
        }

        return $tables;
    }

    /**
     * Help output for crud command
     *
     * @return void
     */
    private function printCommandList()
    {
        echo "\n\e[32m" .
            '      __________  __  ______  ' . "\n" .
            '     / ____/ __ \/ / / / __ \ ' . "\n" .
            '    / /   / /_/ / / / / / / / ' . "\n" .
            '   / /___/ _, _/ /_/ / /_/ /  ' . "\n" .
            '   \____/_/ |_|\____/_____/   ' . "\e[0m\n\n\n";

        $output = [
            'create:{table}                       ',
            'find:{table} {where_condition}       ',
            'update:{table} {primary_key}         ',
            'delete:{table} {primary_key}         ',
        ];

        foreach ($output as $line) {
            echo $line . "\n";
        }

        exit;
    }

    public static function error($msg)
    {
        die("\e[31m" . $msg . "\e[0m\n");
    }

    public static function printMessage($msg, $type = 0)
    {
        switch ($type) {
            case 1:
               echo "\e[32m";
                break;

            case 2:
               echo "\e[93m";
                break;
            case 3:
               echo "\e[31m";
               break;

            default:

                break;
        }
        echo $msg."\e[0m";
    }
}
