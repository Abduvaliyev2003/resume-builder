<?php

namespace App\Shared\DTOs;

abstract class BaseDTO
{
    public static function fromArray(array $data): static
    {
        // Filter array keys to only match defined constructor arguments
        $reflection = new \ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();
        
        if ($constructor) {
            $params = $constructor->getParameters();
            $allowedKeys = array_map(fn($p) => $p->getName(), $params);
            $filteredData = array_intersect_key($data, array_flip($allowedKeys));
            return new static(...$filteredData);
        }

        return new static();
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
