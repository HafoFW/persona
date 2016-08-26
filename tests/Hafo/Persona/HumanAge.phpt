<?php

namespace HafoTest;

require __DIR__ . '/../../bootstrap.php';

use Hafo\Persona\HumanAge;
use Tester\TestCase;
use Tester\Assert;

class HumanAgeTest extends TestCase {

    function testYearsAt() {
        $age = new HumanAge((new \DateTimeImmutable)->setDate(2016, 8, 27));

        Assert::equal(0, $age->yearsAt((new \DateTimeImmutable)->setDate(2016, 8, 27)));

        Assert::equal(0, $age->yearsAt((new \DateTimeImmutable)->setDate(2017, 8, 26)));
        Assert::equal(1, $age->yearsAt((new \DateTimeImmutable)->setDate(2017, 8, 27)));

        Assert::equal(0, $age->yearsAt((new \DateTimeImmutable)->setDate(2015, 8, 28)));
        Assert::equal(-1, $age->yearsAt((new \DateTimeImmutable)->setDate(2015, 8, 27)));

        Assert::equal(50, $age->yearsAt((new \DateTimeImmutable)->setDate(2066, 8, 27)));
    }

}

(new HumanAgeTest)->run();
