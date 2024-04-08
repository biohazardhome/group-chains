<?php

namespace Biohazard;

use Closure;
// use Illuminate\Support\Traits\Macroable;
use Spatie\Macroable\Macroable;

class GroupChains {

    use Macroable;

    private $group = [],
        $wrapContainer = null,
        $methods = [],
        $result = null;

    public function __construct(&...$group) {
        $this->group = $group;
    }

    public function __call(string $method, array $arguments) {
        $this->methods[$method] = $arguments;

        if (!static::hasMacro($method)) {
            static::macro($method, function($item, $method, ...$arguments) {
                if (method_exists($item, $method)) {
                    $this->result = $item->{$method}(...$arguments);
                }

                return $this;
            });
        }

        return $this;
    }

    public function wrap($wrap) {
        $this->wrapContainer = $wrap;

        return $this;
    }

    public function run() {
        $result = [];
        foreach($this->group as &$item) {

            if ($this->wrapContainer) {
                if (is_callable($this->wrapContainer)) {
                    $item = call_user_func($this->wrapContainer, $item);
                } else if (class_exists($this->wrapContainer)) {
                    $item = new $this->wrapContainer($item);
                }
            }

            foreach ($this->methods as $name => $arguments) {

                $macro = static::$macros[$name];

                if ($macro instanceof Closure) {
                    $macro = $macro->bindTo($this, static::class);
                }

                $macro($item, $name, ...$arguments);
                $result[] = $this->result;

                $item = $this->result;
            }
        }

        return $result;
    }

}
