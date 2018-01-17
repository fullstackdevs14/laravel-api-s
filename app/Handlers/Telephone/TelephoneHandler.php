<?php

namespace App\Handlers\Telephone;

use libphonenumber\PhoneNumberFormat;

/**
 * Ce gestionnaire gère le format des numéros de téléphone.
 *
 * Class TelephoneHandler
 * @package App\Handlers\Telephone
 */
class TelephoneHandler
{
    /**
     * Cette fonction retourne un numéro de téléphone avec le code de la région si celui-ci n'est pas initialement renseigné.
     * Elle ne fonctionne qu'avec les numéros de téléphone français.
     *
     * @param string $phoneNumber
     * @return \libphonenumber\PhoneNumberUtil|string
     */
    static function frenchNumberFormat
    (
        $phoneNumber
    )
    {
        $frenchTel = phone($phoneNumber, $country_code = 'FR', $format = PhoneNumberFormat::E164);
        return $frenchTel;
    }
}