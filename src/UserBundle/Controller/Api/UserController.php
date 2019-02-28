<?php

namespace UserBundle\Controller\Api;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\RestBundle\Controller\Annotations as Rest;
use UserBundle\Entity\User;
use UserBundle\Form\RegistrationFormType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * Get User.
     *
     * @ApiDoc(
     *   resource = true,
     *   section="User"
     * )
     * @Rest\Get("/api/user/{id}", requirements={"id":"\d+"}, options={"i18n" = false}, name="task_api_get_user")
     * @Rest\Get("/api/user", options={"i18n" = false}, name="task_api_get_current_user")
     * @Rest\View(serializerGroups={"Default", "Details"})
     *
     * @param User $user
     * @param Request $request
     *
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getUserAction(User $user = null, Request $request)
    {
        if (!$user){
            $user = $this->getUser();
        }

        return array(
            'user' => $user,
        );
    }

    /**
     * Create a full user account.
     *
     * @ApiDoc(
     *   resource=true,
     *   section="User",
     *   input="\UserBundle\Form\RegistrationFormType",
     * )
     *
     * @Rest\Post("/api/user/register", options={"i18n" = false}, name="task_api_create_new_user")
     * @Rest\View(serializerGroups = {"Default", "Details"})
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function createNewUserAction(Request $request)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');

        /** @var $dispatcher EventDispatcher */
        $dispatcher = $this->container->get('event_dispatcher');

        /** @var User $user */
        $user = $userManager->createUser();
        $form = $this->createForm(RegistrationFormType::class, $user, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user->setEnabled(true);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, new FormEvent($form, $request));
            $userManager->updateUser($user);
            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('task_api_create_new_user');
                $response = new RedirectResponse($url);
            }
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return array('user' => $user);
        }

        return $form;
    }
}
