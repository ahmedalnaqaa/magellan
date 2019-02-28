<?php

namespace UserBundle\Controller\Api;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class BaseController extends FOSRestController
{

    protected function respond($data, $code = Response::HTTP_OK)
    {
        return View::create()->setData($data)->setStatusCode($code);
    }

    protected function complain(Exception $e)
    {
        return View::create()
            ->setData(['code' => $e->getCode(), 'message'=> $e->getMessage()])
            ->setStatusCode(Response::HTTP_BAD_REQUEST);
    }
}
