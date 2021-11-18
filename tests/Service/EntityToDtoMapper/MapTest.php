<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Tests\Service\EntityToDtoMapper;

use BowlOfSoup\CakeDtoMapper\Exception\DtoMapperException;
use BowlOfSoup\CakeDtoMapper\Service\EntityToDtoMapper;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\DtoSimple;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\DtoSub;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\DtoWithMultipleSub;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\DtoWithSingleSub;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\EntitySimple;
use BowlOfSoup\CakeDtoMapper\Tests\Asset\EntitySub;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    /**
     * @throws DtoMapperException
     */
    public function testMapEntityToSimpleDto(): void
    {
        $entity = new EntitySimple();
        $entity->id = 123;
        $entity->name = 'Phone of Brand X';
        $entity->date_created = '2022-04-01 13:45';
        $entity->price = '2.50';
        $entity->stock = 65;
        $entity->dimensions = [
            'width' => 10,
            'height' => 20,
            'length' => 25,
        ];
        $entity->list = [1, 2, 3];

        /** @var DtoSimple $dtoSimple */
        $dtoSimple = EntityToDtoMapper::map($entity, DtoSimple::class);

        $this->assertSame($entity->id, $dtoSimple->id);
        $this->assertSame($entity->name, $dtoSimple->name);
        $this->assertSame($entity->date_created, $dtoSimple->date_created);
        $this->assertSame($entity->price, $dtoSimple->price);
        $this->assertSame($entity->stock, $dtoSimple->stock);
        $this->assertSame($entity->dimensions, $dtoSimple->dimensions);
        $this->assertSame($entity->dimensions, $dtoSimple->dimensions);

        $this->assertSame(
            '{"id":123,"name":"Phone of Brand X","date_created":"2022-04-01 13:45","price":"2.50","stock":65,"dimensions":{"width":10,"height":20,"length":25},"list":[1,2,3]}',
            json_encode($dtoSimple)
        );
    }

    public function testMapEntityToDtoWithSingleSubDto(): void
    {
        $entitySub = new EntitySub();
        $entitySub->id = 777;
        $entitySub->foo = 'bar';
        $entitySub->hello = 'world';

        $entity = new EntitySimple();
        $entity->id = 123;
        $entity->name = 'Phone of Brand X';
        $entity->stock = 65;
        $entity->entity_sub = $entitySub;

        /** @var DtoWithSingleSub $dtoWithSingleSub */
        $dtoWithSingleSub = EntityToDtoMapper::map($entity, DtoWithSingleSub::class);

        $this->assertSame($entity->id, $dtoWithSingleSub->id);
        $this->assertSame($entity->name, $dtoWithSingleSub->name);
        $this->assertSame($entity->stock, $dtoWithSingleSub->stock);
        $this->assertInstanceOf(DtoSub::class, $dtoWithSingleSub->entity_sub);
        $this->assertSame($entitySub->id, $dtoWithSingleSub->entity_sub->id);
        $this->assertSame($entitySub->foo, $dtoWithSingleSub->entity_sub->foo);
        $this->assertSame($entitySub->hello, $dtoWithSingleSub->entity_sub->hello);

        $this->assertSame(
            '{"id":123,"name":"Phone of Brand X","stock":65,"entity_sub":{"id":777,"foo":"bar","hello":"world"}}',
            json_encode($dtoWithSingleSub)
        );
    }

    public function testMapEntityToDtoWithMultipleSubDto(): void
    {
        $entitySub1 = new EntitySub();
        $entitySub1->id = 777;
        $entitySub1->foo = 'bar';
        $entitySub1->hello = 'world';

        $entitySub2 = new EntitySub();
        $entitySub2->id = 987;
        $entitySub2->foo = 'something else';
        $entitySub2->hello = 'mars';

        $entity = new EntitySimple();
        $entity->id = 123;
        $entity->name = 'Phone of Brand X';
        $entity->stock = 65;
        $entity->entity_sub = [$entitySub1, $entitySub2];

        /** @var DtoWithSingleSub $dtoWithSingleSub */
        $dtoWithSingleSub = EntityToDtoMapper::map($entity, DtoWithMultipleSub::class);

        $this->assertSame($entity->id, $dtoWithSingleSub->id);
        $this->assertSame($entity->name, $dtoWithSingleSub->name);
        $this->assertSame($entity->stock, $dtoWithSingleSub->stock);

        $this->assertIsArray($dtoWithSingleSub->entity_sub);
        $this->assertCount(2, $dtoWithSingleSub->entity_sub);

        $this->assertSame($entitySub1->id, $dtoWithSingleSub->entity_sub[0]->id);
        $this->assertSame($entitySub1->foo, $dtoWithSingleSub->entity_sub[0]->foo);
        $this->assertSame($entitySub1->hello, $dtoWithSingleSub->entity_sub[0]->hello);
        $this->assertSame($entitySub2->id, $dtoWithSingleSub->entity_sub[1]->id);
        $this->assertSame($entitySub2->foo, $dtoWithSingleSub->entity_sub[1]->foo);
        $this->assertSame($entitySub2->hello, $dtoWithSingleSub->entity_sub[1]->hello);

        $this->assertSame(
            '{"id":123,"name":"Phone of Brand X","stock":65,"entity_sub":[{"id":777,"foo":"bar","hello":"world"},{"id":987,"foo":"something else","hello":"mars"}]}',
            json_encode($dtoWithSingleSub)
        );
    }
}
