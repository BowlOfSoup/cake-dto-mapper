<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Tests\Asset;

class DtoWithMultipleSub
{
    public $id;

    public $name;

    public $stock;

    public $entity_sub = [DtoSub::class];
}