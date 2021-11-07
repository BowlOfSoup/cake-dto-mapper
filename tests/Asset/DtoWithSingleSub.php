<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Tests\Asset;

class DtoWithSingleSub
{
    public $id;

    public $name;

    public $stock;

    public $entity_sub = DtoSub::class;
}