<?php

namespace Tests\AppBundle\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tests\AppBundle\DataFixtures\ORM\LoadTaskData;
use Tests\AppBundle\DataFixtures\ORM\LoadUserData;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class TodoControllerTest
 */
class TodoControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->em = $this->container->get('doctrine.orm.default_entity_manager');

        $this->loadFixtures([
            new LoadUserData(),
            new LoadTaskData(),
        ]);
    }

    /**
     * Test List
     */
    public function testList()
    {
        $this->client->request('GET', '/tasks/list');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertTrue(is_array($data));
        $this->assertTrue(count($data) > 0);
        $this->assertArrayHasKey('data', $data);

        $this->assertEquals($data['data'][0]['content'], 'foo bar 1');
    }

    /**
     * Test by id
     */
    public function testById()
    {
        $task = $this->em
            ->getRepository('AppBundle:Task')
            ->findOneBy(['content' => 'foo bar 1']);

        $this->client->request('GET', sprintf('/tasks/%d', $task->getId()));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertTrue(is_array($data));
        $this->assertTrue(count($data) > 0);
        $this->assertArrayHasKey('data', $data);

        $this->assertEquals($data['data'][0]['content'], 'foo bar 1');
    }

    /**
     * Test Complete
     */
    public function testComplete()
    {
        $task = $this->em
            ->getRepository('AppBundle:Task')
            ->findOneBy(['content' => 'foo bar 1']);

        $this->client->request('PATCH', sprintf('/tasks/%d/complete', $task->getId()));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertTrue(is_array($data));
        $this->assertTrue(count($data) > 0);
        $this->assertArrayHasKey('data', $data);

        $this->assertEquals($data['data'][0]['is_completed'], true);
    }

    /**
     * Test new
     */
    public function testNew()
    {
        $text = 'Some new Task';
        $this->client->request('POST', '/tasks/create', ['content' => $text]);
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $content = $response->getContent();

        $data = json_decode($content, true);

        $this->assertTrue(is_array($data));
        $this->assertTrue(count($data) > 0);
        $this->assertArrayHasKey('data', $data);

        $this->assertEquals($data['data'][0]['content'], $text);
    }


    /**
     * Test by id
     */
    public function testDelete()
    {
        $task = $this->em
            ->getRepository('AppBundle:Task')
            ->findOneBy(['content' => 'foo bar 1']);

        $this->client->request('DELETE', sprintf('/tasks/%d', $task->getId()));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $task2 = $this->em
            ->getRepository('AppBundle:Task')
            ->findOneBy(['content' => 'foo bar 1']);

        $this->assertNull($task2);
    }


    /**
     * Run fixtures
     *
     * @param FixtureInterface[] $fixtures
     */
    protected function loadFixtures(array $fixtures)
    {
        $loader = new ContainerAwareLoader($this->container);
        foreach ($fixtures as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }
}
