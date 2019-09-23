<?php

namespace Labstag\Service;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PhoneService
{
    /**
     * @var PhoneNumberUtil
     */
    private $phoneUtil;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Verifie le numéro de téléphone en fonction du pays.
     *
     * @param string $numero Numéro de téléphone
     * @param string $locale code du pays
     *
     * @return array
     */
    public function verif(string $numero, string $locale): array
    {
        $numero      = str_replace([' ', '-', '.'], '', $numero);
        try {
            $numberProto = $this->phoneUtil->parseAndKeepRawInput(
                $numero,
                strtoupper($locale)
            );

            $CountryCodeSource = $numberProto->getCountryCodeSource();
            $nationalNumber    = $numberProto->getNationalNumber();
            $json              = [
                'country'       => $this->phoneUtil->getRegionCodeForNumber(
                    $numberProto
                ),
                'international' => '+' . $numberProto->getNationalNumber(),
                'num'           => $CountryCodeSource . $nationalNumber,
            ];
        } catch (NumberParseException $e) {
            $json['error'] = $e->__toString();
        }

        return $json;
    }
}