<?php


namespace App\Core\Infrastructure\Container\Application\Utils\Validations;


class CPF
{
    /**
     * @param string $cpf
     * @return bool
     */
    public static function validate(string $cpf)
    {
        if (!self::validateDuplicateNumbers($cpf)) {
            return false;
        }

        $firstValidationArray = [10, 9, 8, 7, 6, 5, 4, 3, 2];
        $secondValidationArray = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];
        $sizeFirstValidation = sizeof($firstValidationArray);
        $sizeSecondValidation = sizeof($secondValidationArray);
        $cpfLength = strlen($cpf) - 1;
        $some = 0;

        for ($i = 0; $i < $sizeFirstValidation; $i++) {
            $cpfCharacterNumber = intval($cpf[$i]);
            $some += $cpfCharacterNumber * $firstValidationArray[$i];
        }

        $cpfTemp = $cpf;
        $firstValidationNumber = self::digitValidation($some);
        $cpfTemp[$cpfLength - 1] = $firstValidationNumber;

        $some = 0;
        for ($i = 0; $i < $sizeSecondValidation; $i++) {
            $cpfCharacterNumber = intval($cpfTemp[$i]);
            $some += $cpfCharacterNumber * $secondValidationArray[$i];
        }

        $secondValidationNumber = self::digitValidation($some);
        return self::verifyDigits($cpf, $firstValidationNumber, $secondValidationNumber);
    }

    /**
     * @param string $cpf
     * @return bool
     */
    private static function validateDuplicateNumbers(string $cpf)
    {
        if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
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
     * @param string $cpf
     * @param int $firstValidationNumber
     * @param int $secondValidationNumber
     * @return bool
     */
    private static function verifyDigits(string $cpf, int $firstValidationNumber, int $secondValidationNumber): bool
    {
        $cpfLength = strlen($cpf) - 1;

        if (($firstValidationNumber == intval($cpf[$cpfLength - 1])) && ($secondValidationNumber == intval($cpf[$cpfLength]))) {
            return true;
        }
        return false;
    }
}