<?php

namespace App\Controller\Api;

use App\Controller\Api\BaseApiController;
use App\Entity\Article;
use App\Repository\CommentRepository;
use App\Service\CommentService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comment')]
class ApiCommentController extends BaseApiController
{
    #[Route('/recent', name: 'get_recent_comments')]
    public function getRecentComments(CommentRepository $commentRepository, CommentService $commentService): Response
    {
        return $this->serializedResponse($commentRepository->findBy([], ['creationDate' => 'desc']), 'read-recent-comments', 50);
    }

    #[Route('/{id}', name: 'get_comments')]
    #[ParamConverter('article', class: Article::class)]
    public function getComments(Article $article): Response
    {
        return $this->serializedResponse($article->getComments());
    }

}
