<?php

namespace App\Utils;

use Doctrine\ORM\EntityManager;
use App\Entity\Code;
use App\Repository\CodeRepository;

class CodeUtils
{
    const LETTERS_COUNT = 6;
    const NUMBERS_COUNT = 4;

    const ALLOWED_SYMBOLS = [
        'letters' => ['A', 'B', 'C', 'D', 'E', 'F'],
        'numbers' => [2, 3, 4, 6, 7, 8, 9],
    ];

    /**
     * @param CodeRepository $codeRepository
     * @param int $lettersCount
     * @param int $numbersCount
     * @return string
     */
    public static function generateCode(CodeRepository $codeRepository, int $lettersCount = self::LETTERS_COUNT, int $numbersCount = self::NUMBERS_COUNT)
    {
        while(true) {
            $code = '';
            for ($i = 1; $i <= $lettersCount; $i++) {
                $code .= self::ALLOWED_SYMBOLS['letters'][array_rand(self::ALLOWED_SYMBOLS['letters'])];
                // array_rand($ar, $num) doesn't fit - we have to allow duplicates here
            }
            for ($i = 1; $i <= $numbersCount; $i++) {
                $code .= self::ALLOWED_SYMBOLS['numbers'][array_rand(self::ALLOWED_SYMBOLS['numbers'])];
            }
            $code = str_shuffle($code);
            $existingCode = $codeRepository->findBy(['code' => $code]);
            if (empty($existingCode)) return $code;
        }
    }

    /**
     * @param CodeRepository $codeRepository
     * @param EntityManager $entityManager
     * @param int $nb
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public static function generateCodes(CodeRepository $codeRepository, EntityManager $entityManager, int $nb)
    {
        $codes = [];
        for ($i = 1; $i <= $nb; $i++) {
            $code = new Code();
            $codeString = self::generateCode($codeRepository);
            $code->setCode($codeString);
            $codes[] = $codeString;
            $entityManager->persist($code);
            $entityManager->flush();
        }
        return $codes;
    }

}