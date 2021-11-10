<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Service;

use BowlOfSoup\CakeDtoMapper\Exception\DtoMapperException;
use BowlOfSoup\CakeDtoMapper\Model\Entity\MapToDtoInterface;
use Cake\Datasource\EntityInterface;

/**
 * This class converts an entity into a DTO.
 *
 * - DTO must be defined with public properties
 * - DTO property must be null or indicate -one or many- sub-DTO class(es)
 * - Source entity must implement interface BowOfSoup\CakeDtoMapper\Model\Entity\MapToDtoInterface and return $this->_properties
 *
 * Examples:
 *
 * class SomeDto
 * {
 *      public $name;
 *
 *      // Entity.relation -> SomeDto.relation, where Entity.relation is a sub-entity.
 *      public $relation = \Some\Other\Dto::class;
 *
 *      // Entity.someValue -> SomeDto.someValue, where Entity.someValue is an array of sub-entities.
 *      public $someValue = [\Some\Complete\Other\Dto::class];
 * }
 */
class EntityToDtoMapper
{
    /**
     * @throws DtoMapperException
     *
     * @return object
     */
    public static function map(?EntityInterface $entity, string $mapIntoDto)
    {
        if (null === $entity) {
            return null;
        }

        if (!$entity instanceof MapToDtoInterface) {
            throw DtoMapperException::entityDoesNotImplementInterface(get_class($entity));
        }

        return static::mapInto($mapIntoDto, static::getProperties($entity));
    }

    /**
     * @param mixed|MapToDtoInterface|EntityInterface $from
     *
     * @throws DtoMapperException
     *
     * @return mixed
     */
    private static function getProperties($from)
    {
        if (is_array($from)) {
            $properties = [];
            foreach ($from as $value) {
                $properties[] = static::getProperties($value);
            }

            return $properties;
        }

        if (!$from instanceof MapToDtoInterface) {
            return $from;
        }

        $properties = $from->getProperties();
        foreach ($properties as $key => $property) {
            $properties[$key] = static::getProperties($property);
        }

        return $properties;
    }

    /**
     * @throws DtoMapperException
     *
     * @return object|null
     */
    private static function mapInto(string $mapInto, array $properties)
    {
        static::validateClassExists($mapInto);
        $mapIntoClass = new $mapInto();

        $mapIntoClassProperties = get_object_vars($mapIntoClass);
        foreach ($mapIntoClassProperties as $mapPropertyName => $mapPropertyValue) {
            static::validateDtoPropertyExistsInSourceProperties($mapPropertyName, $properties);

            if (null === $mapPropertyValue) {
                // DTO property is empty, no special handling, just fill the DTO property.
                $mapIntoClass->$mapPropertyName = $properties[$mapPropertyName];

                continue;
            }

            if (is_array($mapPropertyValue) && is_array($properties[$mapPropertyName])) {
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
                // DTO property contains a SINGLE sub-DTO class. Fill accordingly.
                $mapIntoClass->$mapPropertyName = static::mapInto($mapPropertyValue, $properties[$mapPropertyName]);
            }
        }

        return $mapIntoClass;
    }

    /**
     * @param string $dtoPropertyName
     * @param mixed $sourceProperties
     *
     * @throws DtoMapperException
     */
    private static function validateDtoPropertyExistsInSourceProperties(string $dtoPropertyName, $sourceProperties): void
    {
        if (!is_array($sourceProperties) || array_key_exists($dtoPropertyName, $sourceProperties)) {
            return;
        }

        throw DtoMapperException::propertyDoesNotExistInSourceData($dtoPropertyName);
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
