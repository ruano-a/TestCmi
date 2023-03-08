<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Vote;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
	public function __construct(protected EntityManagerInterface $entityManager)
	{

	}

	public function addVotes(Iterable $comments): void
	{
		foreach ($comments as $comment) {
			foreach ($comment->getVotes() as $vote) {
				$comment->addVote($vote->getValue());
			}
		}
	}

	public function voteForComment(User $user, int $commentId, int $value): void
	{
		$comment = $this->entityManager->getRepository(Comment::class)->find($commentId);
		if ($value !== -1 && $value !== 1)
			throw new \Exception('Bad value', 1);
		if (!$comment)
			throw new \Exception('Bad comment id', 1);
		$vote = $this->entityManager->getRepository(Vote::class)->findOneBy(['createdByUser' => $user, 'comment' => $comment]);
		if (!$vote) {
			$vote = new Vote();
			$vote->setCreatedByUser($user);
			$vote->setComment($comment);
			$this->entityManager->persist($vote);
		}
		$vote->initCreationDate();
		$vote->setValue($value);
		$this->entityManager->flush();
	}
}
