<?php

declare(strict_types=1);

namespace App\Utils\Macros;

use Reflection;
use ReflectionClass;

class BaseMacro
{
    public function register()
    {
        $reflection = new ReflectionClass($this);

        $methods = $reflection->getMethods();

        $methods = collect($methods)->whereNotIn('name', 'register')->toArray();

        // Dynamically call all methods
        foreach ($methods as $method) {
            $modifiers = Reflection::getModifierNames($method->getModifiers());

            if (! in_array('static', $modifiers)) {
                $this->{$method->name}();

                continue;
            }

            $this::{$method->name}();
        }
    }
}
