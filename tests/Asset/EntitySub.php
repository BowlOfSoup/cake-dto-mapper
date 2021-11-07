<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Tests\Asset;

use BowlOfSoup\CakeDtoMapper\Model\Entity\MapToDtoInterface;
use Cake\ORM\Entity;

class EntitySub extends Entity implements MapToDtoInterface
{
    public function getProperties(): array
    {
        return $this->_fields;
    }
}