<?php

namespace App\Controller\Api;

use App\Controller\Api\BaseApiController;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
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

    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    #[Route('/post', name: 'post_comment')]
    public function postComment(Request $request, EntityManagerInterface $entityManager): Response
    {
        dump($request);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->initCreationDate();
            $comment->setCreatedByUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush($comment);

            return $this->serializedResponseOk();
        }
        return $this->serializedResponseKo();
    }

    #[Route('/{id}', name: 'get_comments')]
    #[ParamConverter('article', class: Article::class)]
    public function getComments(Article $article): Response
    {
        return $this->serializedResponse($article->getComments());
    }
}
