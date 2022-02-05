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
class EntityToDtoMapper extends AbstractDtoMapper
{
    /**
     * @param null|EntityInterface $from
     *
     * @throws DtoMapperException
     *
     * @return null|object
     */
    public static function map($from, string $mapIntoDto)
    {
        if (null === $from) {
            return null;
        }

        if (!$from instanceof MapToDtoInterface) {
            throw DtoMapperException::entityDoesNotImplementInterface(get_class($from));
        }

        return static::mapInto($mapIntoDto, static::getProperties($from));
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
            foreach ($from as $key => $value) {
                $properties[$key] = static::getProperties($value);
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
}
