<?php

declare(strict_types=1);

namespace BowlOfSoup\CakeDtoMapper\Model\Entity;

interface MapToDtoInterface
{
    public function getProperties(): array;
}
