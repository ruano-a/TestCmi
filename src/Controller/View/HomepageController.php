<?php

namespace App\Controller\View;

use App\Controller\View\BaseViewController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseViewController
{
    #[Route('/', name: 'view_homepage')]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
        ]);
    }
}
