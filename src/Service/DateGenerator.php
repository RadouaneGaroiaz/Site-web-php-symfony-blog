<?php

namespace App\Service;

use App\Entity\Card;
use Symfony\Component\Config\Definition\Exception\Exception;

class DateGenerator
{
    const RESET = 0;
    const HARD = 1.2;
    const MEDIUM = 2.4;
    const EASY = 3.6;

    const LEVEL = [
        'RESET' => self::RESET,
        'HARD' => self::HARD,
        'MEDIUM' => self::MEDIUM,
        'EASY' => self::EASY
    ];

}