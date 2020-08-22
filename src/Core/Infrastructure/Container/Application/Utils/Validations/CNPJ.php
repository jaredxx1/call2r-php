<?php


namespace App\Core\Infrastructure\Container\Application\Utils\Validations;


class CNPJ
{
    /**
     * @param string $cnpj
     * @return bool
     */
    public static function validate(string $cnpj)
    {
        if (!self::validationDuplicateNumbers($cnpj)) {
            return false;
        }

        $firstValidationArray = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $secondValidationArray = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sizeFirstValidation = sizeof($firstValidationArray);
        $sizeSecondValidation = sizeof($secondValidationArray);
        $cnpjLength = strlen($cnpj) - 1;
        $some = 0;

        for ($i = 0; $i < $sizeFirstValidation; $i++) {
            $cnpjCharacterNumber = intval($cnpj[$i]);
            $some += $cnpjCharacterNumber * $firstValidationArray[$i];
        }

        $cnpjTemp = $cnpj;
        $firstValidationNumber = self::digitValidation($some);
        $cnpjTemp[$cnpjLength - 1] = $firstValidationNumber;

        $some = 0;
        for ($i = 0; $i < $sizeSecondValidation; $i++) {
            $cnpjCharacterNumber = intval($cnpjTemp[$i]);
            $some += $cnpjCharacterNumber * $secondValidationArray[$i];
        }

        $secondValidationNumber = self::digitValidation($some);

        return self::verifyDigits($cnpj, $firstValidationNumber, $secondValidationNumber);
    }

    /**
     * @param string $cnpj
     * @return bool
     */
    private static function validationDuplicateNumbers(string $cnpj)
    {
        if ($cnpj == '00000000000000' ||
            $cnpj == '11111111111111' ||
            $cnpj == '22222222222222' ||
            $cnpj == '33333333333333' ||
            $cnpj == '44444444444444' ||
            $cnpj == '55555555555555' ||
            $cnpj == '66666666666666' ||
            $cnpj == '77777777777777' ||
            $cnpj == '88888888888888' ||
            $cnpj == '99999999999999') {
            return false;
        }
        return true;
    }

    /**
     * @param int $some
     * @return int
     */
    private static function digitValidation(int $some): int
    {
        $validationNumber = 0;
        $restDivision = $some % 11;

        if ($restDivision <= 2) {
            $validationNumber = 0;
        }

        if ($restDivision > 2) {
            $validationNumber = 11 - $restDivision;
        }

        return $validationNumber;
    }

    /**
     * @param string $cnpj
     * @param int $firstValidationNumber
     * @param int $secondValidationNumber
     * @return bool
     */
    private static function verifyDigits(string $cnpj, int $firstValidationNumber, int $secondValidationNumber): bool
    {
        $cnpjLength = strlen($cnpj) - 1;

        if (($firstValidationNumber == intval($cnpj[$cnpjLength - 1])) && ($secondValidationNumber == intval($cnpj[$cnpjLength]))) {
            return true;
        }
        return false;
    }
}