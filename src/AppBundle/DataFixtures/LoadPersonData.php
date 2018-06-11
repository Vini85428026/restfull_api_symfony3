<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 11/06/18
 * Time: 08:34
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPersonData extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $person1 = new Person();
        $person1->setFirstName('Mark');
        $person1->setLastName('Ruffalo');
        $person1->setDateOfBirth(new \DateTime('1998-02-02'));

        $manager->persist($person1);
        $manager->flush();
    }
}