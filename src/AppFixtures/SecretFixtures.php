<?php

namespace App\AppFixtures;

use App\Entity\Nemesis;
use App\Entity\Secret;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SecretFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $nemesisRepository = $manager->getRepository(Nemesis::class);
        $nemeses = $nemesisRepository->findAll();
        foreach ($nemeses as $nemesis) {
            // Each nemesis may have 0-3 secrets
            for ($k = 0; $k < $faker->numberBetween(0, 3); $k++) {
                $secret = new Secret();
                $secret->setSecretCode($faker->regexify('[1-9]{16}')); // Random 16-digit secret code
                $nemesis->addSecret($secret);
                $manager->persist($secret);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NemesisFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return [
            "secret_group"
        ];
    }
}
