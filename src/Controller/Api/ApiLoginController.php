<?php

namespace App\Controller\Api;

use App\Controller\Api\BaseApiController;
use App\Service\FacebookService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/login')]
class ApiLoginController extends BaseApiController
{
    #[Route('/facebook', name: 'login_facebook')]
    public function loginWithFacebook(FacebookService $facebookService, Request $request): Response
    {
        $facebookData = json_decode($request->getContent(), true);
        $ret = $facebookService->login($facebookData);

        if ($ret === true)
            return $this->serializedResponseOk();
        return $this->serializedResponseKo($ret);
    }
}
