<?php

namespace Vendi\Theme\Exception;

class UnknownComponentClassException extends \Exception
{
    public function __construct(string $className)
    {
        parent::__construct('The specified component class does not exist: ' . $className);
    }
}
