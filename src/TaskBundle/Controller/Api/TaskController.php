<?php

namespace TaskBundle\Controller\Api;

use TaskBundle\Entity\Task;
use TaskBundle\Form\TaskType;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends FOSRestController
{
    /**
     * Create Task.
     *
     * @ApiDoc(
     *   resource=true,
     *   section="Task",
     *   input="\TaskBundle\Form\TaskType",
     * )
     *
     * @Rest\Post("/api/task/create", options={"i18n" = false}, name="api_task_create")
     * @Rest\View(serializerGroups = {"Default"})
     *
     * @param Request $request
     *
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createTaskAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Task $task */
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));
        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            return $task;
        }

        return $form;
    }

    /**
     * Delete Task.
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Task",
     *   statusCodes={
     *       204="Returned when successfully Deleted",
     *       404="Returned when Task is not found",
     *       403="Returned when you are trying to remove task that not yours."
     *   },
     *
     * )
     * @Rest\Delete("/api/task/{id}/delete", options={"i18n" = false}, name="task_api_task_delete")
     *
     * @param Task $task
     * @return Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteTaskAction(Task $task)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * Edit Task.
     *
     * @ApiDoc(
     *   resource=true,
     *   section="Task",
     *   input="\TaskBundle\Form\TaskType"
     * )
     *
     * @Rest\Post("/api/task/{id}/edit", requirements={"id":"\d+"}, options={"i18n" = false}, name="task_api_task_edit")
     * @Rest\View(serializerGroups = {"Default"})
     *
     * @param Task $task
     * @param Request $request
     *
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editTaskAction(Task $task, Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TaskType::class, $task, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($task);
            $em->flush();

            return $task;
        }

        return $form;
    }

    /**
     * Get Tasks.
     *
     * @ApiDoc(
     *   resource = true,
     *   section="Task",
     * )
     *
     * @Rest\Get("/api/task", name="task_api_get_tasks")
     * @Rest\View(serializerGroups = {"Default"})
     * @return array
     */
    public function getTasksAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('TaskBundle:Task')->findAll();

        return $tasks;
    }

    /**
     * Change task status.
     *
     * @ApiDoc(
     *   resource=true,
     *   section="Task",
     *   description="Change task status",
     * )
     *
     * @Rest\Post("/api/task/{id}/status", requirements={"id":"\d+"}, options={"i18n" = false}, name="task_api_change_task_status")
     * @Rest\QueryParam(name="status", requirements="\d+", default=1, description="Task status.")
     * @Rest\View(serializerGroups = {"Default"})
     *
     * @param Task $task
     * @param Request $request
     *
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeTaskStatusAction(Task $task, Request $request)
    {
        if ($request->isMethod('post') && $request->get('status')) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $status = $request->get('status');
            $task->setStatus($status);
            $em->persist($task);
            $em->flush();

            return $task;
        }
        return true;
    }
}