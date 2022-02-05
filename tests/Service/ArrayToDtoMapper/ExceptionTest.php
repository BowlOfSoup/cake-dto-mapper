<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Tests\Service\ArrayToDtoMapper;

use BowlOfSoup\CakeDtoMapper\Exception\DtoMapperException;
use BowlOfSoup\CakeDtoMapper\Service\ArrayToDtoMapper;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\DtoSimple;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testValueIsNotAnArray(): void
    {
        $array = 'string';

        $this->expectException(DtoMapperException::class);
        $this->expectExceptionMessage('Value is not an associative array.');

        ArrayToDtoMapper::map($array, DtoSimple::class);
    }

    public function testArrayIsNotAssociative(): void
    {
        $array = ['a', 'b', 'c'];

        $this->expectException(DtoMapperException::class);
        $this->expectExceptionMessage('Value is not an associative array.');

        ArrayToDtoMapper::map($array, DtoSimple::class);
    }
}