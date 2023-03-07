<?php

namespace App\Controller\Api;

use App\Controller\Api\BaseApiController;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/article')]
class ApiArticleController extends BaseApiController
{
    #[Route('/{id}', name: 'get_article')]
    #[ParamConverter('article', class: Article::class)]
    public function getArticle(Article $article): Response
    {
        return $this->serializedResponse($article);
    }
}
