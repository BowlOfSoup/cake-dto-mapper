<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Tests\Service\ArrayToDtoMapper;

use BowlOfSoup\CakeDtoMapper\Service\ArrayToDtoMapper;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\DtoSimple;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    public function testMapEntityToSimpleDto(): void
    {
        $array = [
            'id' => 123,
            'name' => 'Phone of Brand X',
            'date_created' => '2022-04-01 13:45',
            'price' => '2.50',
            'stock' => 65,
            'dimensions' => [
                'width' => 10,
                'height' => 20,
                'length' => 25,
            ],
            'list' => [1, 2, 3],
        ];

        /** @var DtoSimple $dtoSimple */
        $dtoSimple = ArrayToDtoMapper::map($array, DtoSimple::class);

        $this->assertSame($array['id'], $dtoSimple->id);
        $this->assertSame($array['name'], $dtoSimple->name);
        $this->assertSame($array['date_created'], $dtoSimple->date_created);
        $this->assertSame($array['price'], $dtoSimple->price);
        $this->assertSame($array['stock'], $dtoSimple->stock);
        $this->assertSame($array['dimensions'], $dtoSimple->dimensions);

        $this->assertSame(
            '{"id":123,"name":"Phone of Brand X","date_created":"2022-04-01 13:45","price":"2.50","stock":65,"dimensions":{"width":10,"height":20,"length":25},"list":[1,2,3]}',
            json_encode($dtoSimple)
        );
    }
}