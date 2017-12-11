<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUserLoadTaskDataData
 */
class LoadTaskData extends AbstractFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::getTasks() as $taskKey => $taskInfo) {
            $task = new Task();

            $user = $this->getReference(sprintf('user:%s', $taskInfo['user']));

            $task
                ->setUser($user)
                ->setContent($taskInfo['content'])
                ->setIsCompleted($taskInfo['isCompleted'])
                ->setCreatedAt(new \DateTime());

            $manager->persist($task);
        }

        $manager->flush();
    }

    /**
     * Get tasks
     *
     * @return array
     */
    public static function getTasks()
    {
        return [
            'task1' => [
                'user' => 'user1',
                'content' => 'foo bar 1',
                'isCompleted' => false,
            ],
            'task2' => [
                'user' => 'user1',
                'content' => 'foo bar 2',
                'isCompleted' => false,
            ],
            'task3' => [
                'user' => 'user2',
                'content' => 'foo bar 3',
                'isCompleted' => true,
            ],
            'task4' => [
                'user' => 'user2',
                'content' => 'foo bar 4',
                'isCompleted' => false,
            ],

        ];
    }
}