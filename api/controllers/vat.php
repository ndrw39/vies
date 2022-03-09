<?php

namespace Api\Controllers;

use Api\Common\Database;
use Api\Common\Jwt;
use Api\Models\Users;

class Vat extends BaseController
{
    const CLIENT_PATH = "https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";

    public static function index(object $request): string
    {
        if (self::validate(['countryCode', 'vatNumber', 'jwt']) === false) {
            return self::show(['error' => 'Forbidden'], 403);
        }

        $countryCode = trim($request->countryCode);
        $vatNumber = trim($request->vatNumber);

        $userData = Jwt::decode($request->jwt);
        $users = new Users(Database::connect());

        $successToken = !empty($userData->data->id) || empty($users->getList(['id' => $userData->data->id]));
        if ($successToken === false) {
            return self::show(['error' => 'Forbidden'], 403);
        }

        $client = new \SoapClient(self::CLIENT_PATH);
        $result = $client->checkVat([
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber
        ]);

        $text = "The VAT number you are requesting is not valid. Either the Number is not active or not
        allocated. Please double-check that you are entering the right VAT Number.";

        if ($result->valid === true) {
            $users->update(['vat' => $vatNumber], ['id' => $userData->data->id]);
            $text = "Your VAT Number has been successfully validated. Your company legal data was updated
            according to the VIES database.";
        }

        return self::show(['success' => true, 'text' => $text, 'valid' => $result->valid]);
    }
}