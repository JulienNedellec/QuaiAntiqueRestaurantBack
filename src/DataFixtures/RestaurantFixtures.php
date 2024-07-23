<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;

class RestaurantFixtures extends Fixture
{
    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i = 1; $i <= 20; $i++) {
            $openingTimeAm = $faker->time($format = 'H:i');
            $closingTimeAm = $faker->time($format = 'H:i');
            $openingTimePm = $faker->time($format = 'H:i');
            $closingTimePm = $faker->time($format = 'H:i');

            $restaurant = (new Restaurant())
                ->setName($faker->company())
                ->setDescription($faker->text())
                ->setAmOpeningTime([$openingTimeAm, $closingTimeAm]) // Supposant que le tableau contient l'heure d'ouverture et de fermeture AM
                ->setPmOpeningTime([$openingTimePm, $closingTimePm]) // Supposant que le tableau contient l'heure d'ouverture et de fermeture PM
                ->setMaxGuest(random_int(10,50))
                ->setCreatedAt(new DateTimeImmutable());
            $manager->persist($restaurant);
            $this->addReference("restaurant" . $i, $restaurant);
        }
        $manager->flush();
    }
}