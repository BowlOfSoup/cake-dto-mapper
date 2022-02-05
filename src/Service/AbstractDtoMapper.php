<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Service;

use BowlOfSoup\CakeDtoMapper\Exception\DtoMapperException;

abstract class AbstractDtoMapper
{
    /**
     * @param mixed $from
     *
     * @return null|object
     */
    abstract public static function map($from, string $mapIntoDto);

    /**
     * @throws DtoMapperException
     *
     * @return object|null
     */
    protected static function mapInto(string $mapInto, array $properties)
    {
        static::validateClassExists($mapInto);
        $mapIntoClass = new $mapInto();

        $mapIntoClassProperties = get_object_vars($mapIntoClass);
        foreach ($mapIntoClassProperties as $mapPropertyName => $mapPropertyValue) {
            if (!array_key_exists($mapPropertyName, $properties)) {
                $mapIntoClass->$mapPropertyName = null;

                continue;
            }

            if (null === $mapPropertyValue) {
                // DTO property is empty, no special handling, just fill the DTO property.
                $mapIntoClass->$mapPropertyName = $properties[$mapPropertyName];

                continue;
            }

            if (is_array($mapPropertyValue)) {
                // Multiple sub-classes implied in DTO.

                if (!is_array($properties[$mapPropertyName])) {
                    // Multiple sub-classes implied in DTO, but (from) property does not contain an array.
                    $mapIntoClass->$mapPropertyName = [];

                    continue;
                }

                // DTO property contains an array of sub-DTO's. Fill accordingly.
                $dtoClass = reset($mapPropertyValue);
                static::validateClassExists($dtoClass);

                $mapIntoClass->$mapPropertyName = [];
                foreach ($properties[$mapPropertyName] as $arrayProperty) {
                    if (!is_array($arrayProperty)) {
                        // Only arrays can be mapped to a DTO, continue next property value.
                        continue;
                    }

                    $mapIntoClass->$mapPropertyName[] = static::mapInto($dtoClass, $arrayProperty);
                }

                continue;
            }

            if (class_exists($mapPropertyValue)) {
                // Single sub-class implied in DTO. Fill accordingly (null values allowed).

                $mapIntoClass->$mapPropertyName = null !== $properties[$mapPropertyName]
                    ? static::mapInto($mapPropertyValue, $properties[$mapPropertyName])
                    : null;
            }
        }

        return $mapIntoClass;
    }

    /**
     * @throws DtoMapperException
     */
    private static function validateClassExists(string $className): void
    {
        if (class_exists($className)) {
            return;
        }

        throw DtoMapperException::classDoesNotExist($className);
    }
}