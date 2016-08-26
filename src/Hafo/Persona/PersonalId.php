<?php

namespace Hafo\Persona;

/**
 * An interface for extracting information from personal identification number.
 */
interface PersonalId {

    /**
     * Returns personal ID number in a normalized format.
     *
     * @return string
     */
    function personalId();

    /**
     * Returns age info.
     *
     * @return Age
     */
    function age();

    /**
     * Returns gender info.
     *
     * @return string Gender::MALE or Gender::FEMALE
     */
    function gender();

}
