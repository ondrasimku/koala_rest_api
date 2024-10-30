<?php

namespace App\AppFixtures;

use App\Entity\Character;
use App\Factory\CharacterFactory\CharacterFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CharacterNoNemesisFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Initialize Faker
        $faker = Factory::create();

        // Create a few characters
        for ($i = 0; $i < 5; $i++) {
            // Generate data using Faker
            $name = $faker->firstName;
            $gender = $faker->randomElement(['male', 'female', 'non-binary', 'm', 'f']);
            $ability = $faker->jobTitle;
            $minimalDistance = number_format($faker->randomFloat(2, 1.0, 10.0), 2, '.', '');
            $weight = number_format($faker->randomFloat(2, 45, 100), 2, '.', '');
            $born = $faker->dateTimeBetween('-50 years', '-20 years');
            $inSpaceSince = $faker->dateTimeBetween('-10 years', 'now');
            $beerConsumption = $faker->numberBetween(100, 100000);
            $knowsTheAnswer = $faker->boolean(80);

            // Use the factory to create a character
            $character = CharacterFactory::create(
                $name,
                $gender,
                $ability,
                $minimalDistance,
                $weight,
                $born,
                $inSpaceSince,
                $beerConsumption,
                $knowsTheAnswer
            );
            $manager->persist($character);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            "character_no_nemesis_group"
        ];
    }

    public function getDependencies(): array
    {
        return [
            NemesisFixtures::class
        ];
    }
}
