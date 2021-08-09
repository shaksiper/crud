<?php

namespace Helpers;

class Request
{
    protected string $request;
    protected $type = null;

    public function __construct(string $request, string $type)
    {
        $this->request = $request;
        $this->type = $type;
    }

    public function validate($constraints)
    {
        $validator = new Validator();
        $validationMap = $validator->validate($this, $constraints);
        return $validationMap;
    }

    public function getType()
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->request;
    }
}
