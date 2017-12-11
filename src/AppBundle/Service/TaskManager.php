<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Entity\Repository\TaskRepository;
use AppBundle\Entity\Task;
use AppBundle\Exception\FormNotValidException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class TaskManager
 */
class TaskManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TaskRepository
     */
    protected $taskRepo;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * TaskManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->taskRepo = $this->em->getRepository('AppBundle:Task');
    }

    /**
     * Injects token storage
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Get list by user
     *
     * @param User $user
     *
     * @return Task[]
     */
    public function getListByUser(User $user)
    {
        return $this->taskRepo->getByUser($user);
    }

    /**
     * Create
     *
     * @param FormInterface $form
     *
     * @return Task
     *
     * @throws FormNotValidException
     */
    public function create(FormInterface $form)
    {
        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }

        /** @var Task $task */
        $task = $form->getData();

        $task->setUser($this->getUser());
        $task->setCreatedAt(new \DateTime());

        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    /**
     * Complete
     *
     * @param Task $task
     *
     * @return Task
     */
    public function complete(Task $task)
    {
        $this->access($this->getUser(), $task);

        $task->setIsCompleted(true);

        $this->em->flush();

        return $task;
    }

    /**
     * Delete Task
     *
     * @param Task $task
     */
    public function deleteTask(Task $task)
    {
        $this->access($this->getUser(), $task);

        $this->em->remove($task);
        $this->em->flush();
    }

    /**
     * Access
     *
     * @param User $user
     * @param Task $task
     */
    public function access(User $user, Task $task)
    {
        if ($user->getId() !== $task->getUser()->getId()) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Get current user
     *
     * @return User|null
     */
    protected function getUser()
    {
        return $user = $this->em
            ->getRepository('AppBundle:User')
            ->findOneBy(['username' => 'User 1']);

        if ($this->tokenStorage->getToken()) {
            return $this->tokenStorage->getToken()->getUser();
        }

        return null;
    }
}
