<?php

namespace App\Data;

use App\Entity\Equipment;

class EquipmentFilter
{
    /**
     * @var Equipment[]
     */
    public array $equipments = []; //on fait public pour ne pas avoir a utiliser des getters et des setters
}

