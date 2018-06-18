<?php
/**
 * Created by PhpStorm.
 * User: marcosvinicius
 * Date: 18/06/18
 * Time: 14:12
 */

namespace AppBundle\DataFixtures;


use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('vini_doe');
        $user1->setApiKey('jPlXPRNgNBQrW7dJ9CsNcnPz');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('vick_doe');
        $user2->setApiKey('dwgfsjfk2d2awfddassdgydg');
        $manager->persist($user2);

        $manager->flush();
    }
}