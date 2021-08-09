<?php

namespace Helpers;

class Validator
{
    /*
    * $ruleBook is the array of constraints. They include which function corresponds to each constraint
    * */
    protected static $ruleBook = [
        'string' => 'isString',
        'integer' => 'isInteger',
        'unique' => 'isUnique',
        /* 'current_timestamp' => 'time', */
    ];

    public function validate(Request $request, array $constraints): array
    {
        $assert = function ($list) use ($request): array {
            $resultMap = [];
            foreach ($list as $map) {
                foreach ($map as $assertion => $constraint) {
                    $resultMap[$assertion] = $this->{$assertion}($request, $constraint);
                }
            };

            return $resultMap;
        };

        $ruleMatcher = function ($constraints) {
            $constraint = explode(':', $constraints, 2);
            if (array_key_exists($constraint[0], self::$ruleBook)) {
                /* return $this->ruleBook[$constraint[0]]($target); */
                return [self::$ruleBook[$constraint[0]] =>$constraint[1]];
            }
        };

        $resultMap = array_map($ruleMatcher, $constraints);
        $resultMap = $assert(array_map($ruleMatcher, $constraints)); // $constraints sends each const. in form of string

        dump($resultMap);
        return $resultMap;
    }

    /*
    * @return true if $target is string in lenght of $size
    *
    * */
    private function isString(String $target, $len)
    {
        return strlen($target) <= $len;
    }

    /*
    *@return true if $target is int
    * */
    private function isInteger(String $target)
    {
        return is_numeric($target);
    }

    /**
    *
    *Checks whether the input value is unique
    *
    * @param Request $target is looked in $targetColumn columns of rows
    * @param string $targetTable is where the uniqueness is tried
    *
    */
    private function isUnique(Request $target, $targetTable)
    {
        $onStorage = __DIR__ . '/../Storage/' . sha1('Tables\\'.$targetTable);

        if (file_exists($onStorage)) {
            $table = unserialize(file_get_contents($onStorage));
            return $table->isUnique($target, $target->getType());
        }

        return true;
    }
}
