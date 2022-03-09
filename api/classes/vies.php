<?php

class Vies
{
    const CLIENT_PATH = "https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";

    public static function checkVat(string $countryCode, string $vat): object
    {
        $client = new SoapClient(self::CLIENT_PATH);
        return $client->checkVat([
            'countryCode' => $countryCode,
            'vatNumber' => $vat
        ]);
    }
}