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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends Fixture implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        $user1 = new User();
        $user1->setUsername('vini_doe');
        $user1->setPassword($passwordEncoder->encodePassword($user1, 'vinidoe'));
        $user1->setRoles([User::ROLE_ADMIN]);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('vick_doe');
        $user2 ->setPassword($passwordEncoder->encodePassword($user2, 'vickdoe'));
        $user2->setRoles([User::ROLE_ADMIN]);
        $manager->persist($user2);

        $manager->flush();
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}