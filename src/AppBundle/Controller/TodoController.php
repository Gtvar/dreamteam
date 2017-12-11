<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Exception\FormNotValidException;
use AppBundle\Service\TaskManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class TodoController
 *
 * @Configuration\Route("/tasks")
 */
class TodoController extends Controller
{
    /**
     * @var TaskManager
     */
    protected $taskManager;

    /**
     * TodoController constructor.
     *
     * @param TaskManager $taskManager
     */
    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     * Task list
     *
     * @param Request $request
     *
     * @ApiDoc(
     *      section="Version 1.0",
     *      description="Task list",
     *      statusCodes={
     *          200="OK",
     *      }
     * )
     *
     * @Configuration\Route("/list")
     * @Configuration\Method({"GET"})
     *
     * @return Task[]
     */
    public function listAction(Request $request)
    {
//        $user = $this->getUser();
        $user = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:User')
            ->findOneBy(['username' => 'User 1']);
        $tasks = $this->taskManager->getListByUser($user);

        return $tasks;
    }

    /**
     * Task action
     *
     * @param Task $task
     *
     * @ApiDoc(
     *      section="Version 1.0",
     *      description="Get task by id",
     *      statusCodes={
     *          200="OK",
     *      }
     * )
     *
     * @Configuration\Route("/{id}")
     * @Configuration\Method({"GET"})
     *
     * @return Task
     */
    public function taskAction(Task $task)
    {
        //        $user = $this->getUser();
        $user = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:User')
            ->findOneBy(['username' => 'User 1']);

        $this->taskManager->access($user, $task);

        return $task;
    }

    /**
     * Create Task action
     *
     * @param FormInterface $form
     *
     * @ApiDoc(
     *      section="Version 1.0",
     *      description="Create task",
     *      input="AppBundle\Form\Type\TaskType",
     *      statusCodes={
     *          200="OK",
     *          400="Bad Request:"
     *      }
     * )
     *
     * @Configuration\Route("/create")
     * @Configuration\Method({"POST"})
     *
     * @ParamConverter(
     *     name="form",
     *     class="AppBundle\Form\Type\TaskType"
     * )
     *
     * @return Task|FormInterface
     */
    public function createAction(FormInterface $form)
    {
        try {
            $task = $this->taskManager->create($form);
        } catch (FormNotValidException $e) {
            return $e->getForm();
        }

        return $task;
    }

    /**
     * Complete Task action
     *
     * @param Task $task
     *
     * @ApiDoc(
     *      section="Version 1.0",
     *      description="Complete task",
     *      statusCodes={
     *          200="OK"
     *      }
     * )
     *
     * @Configuration\Route("/{id}/complete")
     * @Configuration\Method({"PATCH"})
     *
     * @return Task
     */
    public function completeAction(Task $task)
    {
        $task = $this->taskManager->complete($task);

        return $task;
    }

    /**
     * Delete Task action
     *
     * @param Task $task
     *
     * @ApiDoc(
     *      section="Version 1.0",
     *      description="Delete task",
     *      statusCodes={
     *          200="OK"
     *      }
     * )
     *
     * @Configuration\Route("/{id}")
     * @Configuration\Method({"DELETE"})
     *
     * @return void
     */
    public function deleteAction(Task $task)
    {
        $this->taskManager->deleteTask($task);
    }
}
