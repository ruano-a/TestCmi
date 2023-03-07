<?php

namespace App\Controller\View;

use App\Controller\View\BaseViewController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends BaseViewController
{
    #[Route('/{id}', name: 'view_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'form' 
        ]);
    }
}
