<?php

namespace Hafo\Persona;

class HumanAge implements Age {

    private $born;

    function __construct(\DateTimeInterface $born) {
        $this->born = $born;
    }

    function dateBorn() {
        return $this->born;
    }

    function yearsAt(\DateTimeInterface $when) {
        $diff = $this->dateBorn()->diff($when);
        return $diff->y * ($diff->invert ? -1 : 1);
    }

}
