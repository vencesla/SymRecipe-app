<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredient;
use Faker\Generator;
use Faker\Factory;

class AppFixtures extends Fixture
{

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct() {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $ingredients = [];
        for($i = 0; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
               ->setPrice(mt_rand(0, 100));
               $ingredients[] = $ingredient;
               $manager->persist($ingredient);
        }

        for( $j = 0; $j <= 25; $j++) {
            $recepie = new Recipe();
            $recepie->setName($this->faker->word())
            ->setPrice(mt_rand(0, 50))
            ->setTime(mt_rand(0,1) == 1 ? mt_rand(1, 1440): null)
            ->setNbPeople(mt_rand(0,1) == 1 ? mt_rand(1, 50) : null)
            ->setDifficulty(mt_rand(0,1) == 1 ? mt_rand(1, 5): null)
            ->setDescription($this->faker->text())
            ->setPrice(mt_rand(0,1) == 1 ? mt_rand(1, 1000): null)
            ->setIsFavorite(mt_rand(0,1) == 1 ? true : false);

            for($k=0; $k < mt_rand(1,5); $k++) {
                $recepie->addIngredient($ingredients[mt_rand(0, count($ingredients) -1)]);
            }

            $manager->persist($recepie);
        }

        $manager->flush();
    }
}