<?php

namespace HafoTest;

require __DIR__ . '/../../bootstrap.php';

use Hafo\Persona\CzechPersonalId;
use Tester\TestCase;
use Tester\Assert;

class CzechPersonalIdTest extends TestCase {

    function testBullshitRules() {

        $expected = [
            // nesmysly
            '1234567890' => NULL,
            'abcdefghij' => NULL,

            // do roku 1953 včetně chybí kontrolní číslice, dělitelnost se neřeší
            '540101 / 000'  => [1854, 1, 1, \Hafo\Persona\Gender::MALE],
            '545101 / 000'  => [1854, 1, 1, \Hafo\Persona\Gender::FEMALE],
            '530101 / 000'  => [1953, 1, 1, \Hafo\Persona\Gender::MALE],
            '535101 / 000'  => [1953, 1, 1, \Hafo\Persona\Gender::FEMALE],

            // od roku 1954 se používá kontrolní číslice (součet prvních 9 čísel děleno 11 vyjde zbytek poslední číslo)
            '540101 / 1231' => [1954, 1, 1, \Hafo\Persona\Gender::MALE],
            '530101 / 1232' => [2053, 1, 1, \Hafo\Persona\Gender::MALE],
            '540101 / 1232' => NULL,
            '530101 / 1231' => NULL,

            // od roku 2004 může být k měsíci přičteno 20
            '047101 / 1233' => [2004, 1, 1, \Hafo\Persona\Gender::FEMALE],
            '042101 / 1239' => [2004, 1, 1, \Hafo\Persona\Gender::MALE],
            '538231 / 1231' => [2053, 12, 31, \Hafo\Persona\Gender::FEMALE],
            '533231 / 1237' => [2053, 12, 31, \Hafo\Persona\Gender::MALE],
            '037101 / 1243' => NULL,
            '032101 / 1249' => NULL,
            '538231 / 1230' => NULL,
            '533231 / 1236' => NULL,

            // součet devíti cifer děleno 11 vyjde zbytek 10 => platí, i když není dělitelné 11
            '780123 / 3540' => [1978, 1, 23, \Hafo\Persona\Gender::MALE],
        ];

        foreach($expected as $id => $data) {
            if($data === NULL) {
                Assert::exception(function () use ($id) {
                    new \Hafo\Persona\CzechPersonalId($id);
                }, \Hafo\Persona\InvalidPersonalIdException::class);
            } else {
                list($year, $month, $day, $gender) = $data;
                $pid = new \Hafo\Persona\CzechPersonalId($id);
                $born = $pid->age()->dateBorn();
                Assert::equal($year, (int)$born->format('Y'), $id);
                Assert::equal($month, (int)$born->format('n'), $id);
                Assert::equal($day, (int)$born->format('d'), $id);
                Assert::equal($gender, $pid->gender(), $id);
            }
        }
    }

    function testNormalizeFormat() {
        $expected = [
            '540101 / 000' => '540101/000',
            '540101 000' => '540101/000',
            '540101/000' => '540101/000',
            '540101000' => '540101/000',
            540101001 => '540101/001'
        ];
        foreach($expected as $input => $output) {
            Assert::equal($output, (new CzechPersonalId($input))->personalId());
        }
    }

}

(new CzechPersonalIdTest)->run();
