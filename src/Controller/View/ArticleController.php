<?php

namespace App\Controller\View;

use App\Controller\View\BaseViewController;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends BaseViewController
{
    #[Route('/{id}', name: 'view_article')]
    public function index($id): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());
        return $this->render('article/index.html.twig', [
            'articleId' => $id,
            'form'      => $form
        ]);
    }
}
