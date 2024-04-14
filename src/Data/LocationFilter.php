<?php

namespace App\Data;


use App\Entity\City;
use App\Entity\Department;
use App\Entity\Region;

class LocationFilter
{
    /**
     * @var Region[]
     */
    public array $regions = []; //on fait public pour ne pas avoir a utiliser des getters et des setters




    /**
     * @var Department[]
     */
    public array $departments = []; //on fait public pour ne pas avoir a utiliser des getters et des setters


    /**
     * @var City[]
     */
    public array $cities = []; //on fait public pour ne pas avoir a utiliser des getters et des setters



}

