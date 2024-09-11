<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;

class PasswordGeneratorService
{
    /**
     * Génère un mot de passe aléatoire de 8 caractères comprenant Maj, Min, Num, Caractère spécial
     */
    public function generateCustomPassword($length = 8)
    {
        if ($length < 4) {
            throw new \InvalidArgumentException('La longueur du mot de passe doit être d\'au moins 4 caractères.');
        }

        $uppercase = Str::random(1);
        $lowercase = Str::random(1);
        $number = rand(0, 9);
        $specialChars = ['@', '#', '$', '%', '^', '&', '*'];
        $specialChar = $specialChars[array_rand($specialChars)];

        $remainingLength = $length - 4;
        $allChars = Str::random($remainingLength);

        $password = $uppercase . $lowercase . $number . $specialChar . $allChars;

        return str_shuffle($password);
    }
}

