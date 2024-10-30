<?php

namespace App\Factory\CharacterFactory;

use App\Entity\Character;

class CharacterFactory
{
    public static function create(
        string $name,
        string $gender,
        string $ability,
        string $minimalDistance,
        string $weight,
        \DateTimeInterface $born,
        \DateTimeInterface $inSpaceSince,
        int $beerConsumption,
        bool $knowsTheAnswer
    ): Character {
        // Create a new character instance
        $character = new Character();

        // Set character properties
        $character->setName($name);
        $character->setGender($gender);
        $character->setAbility($ability);
        $character->setMinimalDistance($minimalDistance);
        $character->setWeight($weight);
        $character->setBorn($born);
        $character->setInSpaceSince($inSpaceSince);
        $character->setBeerConsumption($beerConsumption);
        $character->setKnowsTheAnswer($knowsTheAnswer);

        return $character;
    }
}