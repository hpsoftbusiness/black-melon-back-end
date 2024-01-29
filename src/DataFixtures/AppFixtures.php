<?php

namespace App\DataFixtures;

use App\Entity\Programmers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 40; $i++) {
            $programmer = new Programmers();
            $programmer->setName($faker->firstName);
            $programmer->setSurname($faker->lastName);
            $programmer->setBirthDate($faker->dateTimeBetween('-40 years', 'now'));
            $programmer->setHeight($faker->numberBetween(150, 200));

            // 25% chance of not setting the link to GitHub
            if ($faker->boolean(25)) {
                $programmer->setLinkToGithub(null);
            } else {
                $programmer->setLinkToGithub($faker->imageUrl());
            }

            $manager->persist($programmer);
        }

        $manager->flush();
    }
}
