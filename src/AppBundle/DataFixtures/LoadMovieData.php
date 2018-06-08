<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 08/06/18
 * Time: 10:25
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMovieData extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */public function load(ObjectManager $manager)
     {
        $movie1 = new Movie();
        $movie1->setTitle('The Avengers');
        $movie1->setYear(2009);
        $movie1->setTime(180);
        $movie1->setDescription('Best Movie of heroes');

        $manager->persist($movie1);
        $manager->flush();
     }
}