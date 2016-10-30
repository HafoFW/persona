<?php

namespace Hafo\Persona;

/**
 * An interface for working with age.
 */
interface Age {

    /**
     * Returns person's age in years at a given moment.
     *
     * @param \DateTimeInterface $when
     * @return int
     */
    function yearsAt(\DateTimeInterface $when);

    /**
     * Returns person's born date.
     *
     * @return \DateTimeInterface
     */
    function dateBorn();

    /**
     * Returns person's next birthday.
     *
     * @param \DateTimeInterface $since
     * @return \DateTimeInterface
     */
    function nextBirthday(\DateTimeInterface $since);

}
