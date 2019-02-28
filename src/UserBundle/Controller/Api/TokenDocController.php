<?php

namespace UserBundle\Controller\Api;

use FOS\OAuthServerBundle\Controller\TokenController;
use FOS\UserBundle\Doctrine\UserManager;
use UserBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use OAuth2\OAuth2;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;

class TokenDocController extends TokenController
{

    /** @var UserManager $userManager */
    protected  $userManager;

    /**
     * @DI\InjectParams({
     *      "server" = @DI\Inject("fos_oauth_server.server"),
     *      "userManager" = @DI\Inject("fos_user.user_manager"),
     * })
     *
     * @param OAuth2 $server
     */
    public function __construct(OAuth2 $server, UserManager $userManager)
    {
        parent::__construct($server);
        $this->userManager = $userManager;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   section="Oauth2"
     * )
     *
     * @Rest\Get("oauth/token", options={"i18n" = false}, name="task_api_oauth_token")
     * @Rest\QueryParam(name="access_token", description="Access Token")
     * @Rest\QueryParam(name="client_id", description="Client ID",)
     * @Rest\QueryParam(name="client_secret", description="Client Secret")
     * @Rest\QueryParam(
     *     name="grant_type",
     *     description="{ password, client_credentials, refresh_token }"
     * )
     * @Rest\QueryParam(name="username", description="Username")
     * @Rest\QueryParam(name="password", description="Password")
     * @Rest\QueryParam(name="refresh_token", description="Refresh Token")
     * @Rest\View()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function tokenAction(Request $request)
    {
        $result = parent::tokenAction($request);

        return $result;
    }

    /**
     * Oauth2 Login
     *
     * @ApiDoc(
     *   resource = true,
     *   section="Oauth2",
     *   statusCodes={
     *       200 = "On successfull login",
     *       422 = "On unprocessable login"
     *   }
     * )
     *
     * @Rest\Post("oauth/token/login", options={"i18n" = false}, name="task_api_oauth_token_login")
     * @Rest\RequestParam(name="client_id", description="Client ID",)
     * @Rest\RequestParam(name="client_secret", description="Client Secret")
     * @Rest\RequestParam(name="username", description="Username")
     * @Rest\RequestParam(name="password", description="Password")
     * @Rest\RequestParam(name="token", description="3rd Party Token to connect after login")
     * @Rest\View()
     *
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        // user parameter
        $userName = $request->get('username');
        /** @var User $user */
        $user = $this->userManager->findUserByUsernameOrEmail($userName);
        // Check if user exists.
        if ($user) {
            // set username
            $request->request->set('username', $user->getUsername());
        }
        // set grant-type
        $request->request->set('grant_type', 'password');
        // generate access token
        $result = parent::tokenAction($request);

        return $result;
    }
}
