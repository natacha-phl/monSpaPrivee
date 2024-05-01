<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase //classe qui est fournie par php unite qui implémente plusieurs classes extend
{
    public function testSomething(): void
    {
        $this->assertTrue(true);// tout ce qui est à l'intérieur du asstertTrue  est bien vrai et que si c'est vrai le test est réussi
    }
}
