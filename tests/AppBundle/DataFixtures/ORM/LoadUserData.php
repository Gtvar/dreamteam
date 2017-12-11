<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUserData
 */
class LoadUserData extends AbstractFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::getUsers() as $userKey => $userInfo) {
            $user = new User();

            $user
                ->setUsername($userInfo['username'])
                ->setPassword($userInfo['password'])
                ->setCreatedAt(new \DateTime());

            $this->setReference(sprintf('user:%s', $userKey), $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * Get users
     *
     * @return array
     */
    public static function getUsers()
    {
        return [
            'user1' => [
                'username' => 'User 1',
                'password' => '123',
            ],
            'user2' => [
                'username' => 'User 2',
                'password' => '123',
            ],
        ];
    }
}