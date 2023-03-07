<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/*
 * For users not using facebook. Not useful currently, but necessary for the firewall to exist.
 */
class DefaultLoginController extends BaseController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}