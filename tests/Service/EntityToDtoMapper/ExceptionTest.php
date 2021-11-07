<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Tests\Service\EntityToDtoMapper;

use BowlOfSoup\CakeDtoMapper\Exception\DtoMapperException;
use BowlOfSoup\CakeDtoMapper\Service\EntityToDtoMapper;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\DtoPropertyDoesNotExistException;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\EntitySimple;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testPropertyDoesNotExistInSourceEntity(): void
    {
        $entity = new EntitySimple();
        $entity->id = 123;
        $entity->name = 'Wooden table';

        $this->expectException(DtoMapperException::class);
        $this->expectExceptionMessage('Can\'t map to DTO property i_do_not_exist_in_entity: property does not exist in source data.');

        /** @var DtoPropertyDoesNotExistException $dtoSimple */
        $dtoSimple = EntityToDtoMapper::map($entity, DtoPropertyDoesNotExistException::class);
    }
}