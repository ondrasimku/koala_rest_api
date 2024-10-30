<?php

namespace App\AppFixtures;

use App\Entity\Character;
use App\Entity\Nemesis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NemesisFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $characterRepository = $manager->getRepository(Character::class);
        $characters = $characterRepository->findAll();
        foreach ($characters as $character) {
            // Create 1-4 nemeses for each character
            for ($j = 0; $j < $faker->numberBetween(1, 4); $j++) {
                $nemesis = new Nemesis();
                $nemesis->setIsAlive($faker->boolean());
                $nemesis->setYears($faker->numberBetween(1, 20));
                $character->addNemesis($nemesis);
                $manager->persist($nemesis);
            }
        }

        // Create some nemeses without characters
        for ($i = 0; $i < 2; $i++) {
            $nemesis = new Nemesis();
            $nemesis->setIsAlive($faker->boolean);
            $nemesis->setYears($faker->numberBetween(1, 20));
            $nemesis->setCharacter(null);
            $manager->persist($nemesis);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CharacterFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return [
            "nemesis_group"
        ];
    }
}
