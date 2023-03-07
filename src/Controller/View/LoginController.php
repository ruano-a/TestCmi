<?php

namespace App\Controller\View;

use App\Controller\View\BaseViewController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends BaseViewController
{
    #[Route('/login', name: 'login')]
    public function index(Request $request): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->redirectToRoute('view_homepage');
        return $this->render('login.html.twig', [
        ]);
    }
}
