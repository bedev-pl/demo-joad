<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return new JsonResponse('OK');
    }
}
