<?php

namespace App\Commands;

/*
* Base class for all commands
* Implements the Command Pattern
*/
abstract class Command {
    /**
     * Define in subclass
     * and put the logic of a command
     */
    public abstract function execute(): Result;

    /**
     * Called to create instance 
     * of subclass with parameterns 
     * in constructor and execute it
     */
    public static function call(...$args): Result
    {
        $instance = self::create(...$args);
        return $instance->execute();
    }

    /**
     * Creates instance of subclass
     * with arguments in constructor
     */
    private static function create(...$args): self
    {
        return new static(...$args);
    }
}