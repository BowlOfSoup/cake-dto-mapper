<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Exception;

use Cake\Datasource\EntityInterface;

class DtoMapperException extends \Exception
{
    public static function propertyDoesNotExistInSourceData(string $propertyName): self
    {
        return new static(sprintf('Can\'t map to DTO property %s: property does not exist in source data.', $propertyName));
    }

    public static function classDoesNotExist(string $className): self
    {
        return new static(sprintf('Class %s does not exist.', $className));
    }
}