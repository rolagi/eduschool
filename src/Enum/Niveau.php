<?php

namespace App\Enum;

enum Niveau: string
{
    case SECONDE = 'seconde';
    case PREMIERE = 'premiere';
    case TERMINALE = 'terminale';

    public function label(): string
    {
        return match ($this) {
            self::SECONDE => 'Seconde',
            self::PREMIERE => 'Première',
            self::TERMINALE => 'Terminale',
        };
    }
}
