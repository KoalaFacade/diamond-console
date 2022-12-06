<?php

namespace KoalaFacade\DiamondConsole\Foundation;

use Closure;
use Illuminate\Container\Container;

trait EvaluatesClosures
{
    /**
     * @param  Closure | null  $value
     * @param  array<array-key, mixed>  $parameters
     * @return Closure | mixed | null
     */
    public function evaluate(Closure | null $value, array $parameters = []): mixed
    {
        if ($value instanceof Closure) {
            return Container::getInstance()->call(
                $value,
                $parameters,
            );
        }

        return $value;
    }
}
