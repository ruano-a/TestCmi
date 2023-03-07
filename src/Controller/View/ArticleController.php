<?php

namespace App\Controller\View;

use App\Controller\View\BaseViewController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends BaseViewController
{
    #[Route('/{id}', name: 'view_article')]
    public function index($id): Response
    {
        return $this->render('article/index.html.twig', [
            'articleId' => $id
        ]);
    }
}
