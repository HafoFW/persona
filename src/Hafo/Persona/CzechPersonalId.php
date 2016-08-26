<?php

namespace Hafo\Persona;

/**
 * A personal identification number implementation for Czech republic and Slovakia.
 */
class CzechPersonalId implements PersonalId {

    private $personalId;

    private $gender;

    private $age;

    /**
     * @param string|int $personalId Accepts both string and integer, parts may be separated with a slash and/or space.
     *
     * @throws InvalidPersonalIdException If the personal ID is not valid or is in invalid format.
     */
    function __construct($personalId) {
        if(!$this->personalId = $this->extract($personalId)) {
            throw new InvalidPersonalIdException("Value '$personalId' is not a valid czech personal ID.");
        }
    }

    function personalId() {
        return $this->personalId;
    }

    function gender() {
        return $this->gender;
    }

    function age() {
        return $this->age;
    }

    private function extract($personalId) {
        if(!preg_match('#^\s*(\d\d)(\d\d)(\d\d)[ /]*(\d\d\d)(\d?)\s*$#', $personalId, $matches)) {
            return FALSE;
        }
        list(, $yearPart, $monthPart, $dayPart, $extra, $control) = $matches;
        $year = (int)$yearPart;
        $month = (int)$monthPart;
        $day = (int)$dayPart;

        // process year
        if($control === '') {
            $year += $year < 54 ? 1900 : 1800;
        } else {
            $mod = ($yearPart . $monthPart . $dayPart . $extra) % 11;
            if($mod === 10) {
                $mod = 0;
            }
            if($mod !== (int)$control) {
                return FALSE;
            }
            $year += $year < 54 ? 2000 : 1900;
        }

        // save gender
        if($month > 50) {
            $this->gender = Gender::FEMALE;
        } else {
            $this->gender = Gender::MALE;
        }

        // process month
        if($month > 70 && $year > 2003) {
            $month -= 70;
        } else if($month > 50) {
            $month -= 50;
        } else if($month > 20 && $year > 2003) {
            $month -= 20;
        }

        // validate date
        if(!checkdate($month, $day, $year)) {
            return FALSE;
        }

        // save age
        $this->age = new HumanAge((new \DateTimeImmutable)->setDate($year, $month, $day));

        // normalize format
        return $yearPart . $monthPart . $dayPart . '/' . $extra . $control;
    }

}
