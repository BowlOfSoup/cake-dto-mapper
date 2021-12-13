<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Tests\Service\EntityToDtoMapper;

use BowlOfSoup\CakeDtoMapper\Exception\DtoMapperException;
use BowlOfSoup\CakeDtoMapper\Service\EntityToDtoMapper;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\DtoSimple;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\EntityNoInterface;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\EntitySimple;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testEntityDoesNotExtendInterface(): void
    {
        $entity = new EntityNoInterface();

        $this->expectException(DtoMapperException::class);
        $this->expectExceptionMessage('Entity BowlOfSoup\CakeDtoMapper\Tests\Asset\EntityNoInterface does not extend MapToDtoInterface');

        EntityToDtoMapper::map($entity, DtoSimple::class);
    }

    public function testTargetDtoDoesNotExist(): void
    {
        $entity = new EntitySimple();

        $this->expectException(DtoMapperException::class);
        $this->expectExceptionMessage('Class FooClass does not exist.');

        EntityToDtoMapper::map($entity, 'FooClass');
    }
}