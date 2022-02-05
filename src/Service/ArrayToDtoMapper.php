<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Service;

use BowlOfSoup\CakeDtoMapper\Exception\DtoMapperException;

class ArrayToDtoMapper extends AbstractDtoMapper
{
    /**
     * @param array $from
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

        if (!is_array($from) || !static::isAssociative($from)) {
            throw DtoMapperException::valueIsNotAnAssociativeArray();
        }

        return static::mapInto($mapIntoDto, $from);
    }

    private static function isAssociative(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}