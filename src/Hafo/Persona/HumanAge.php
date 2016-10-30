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

    function nextBirthday(\DateTimeInterface $since) {
        $born = (new \DateTime($this->dateBorn()->format(\DateTime::ISO8601)));

        $birthday = $born->modify('+' . $since->format('Y') - $born->format('Y') . ' years');
        if($birthday < $since) {
            $birthday->modify('+1 year');
        }
        return $birthday;
    }

}
