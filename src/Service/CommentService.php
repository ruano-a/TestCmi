<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;

class CommentService
{
	// Doing it by hand allows more precise and more simple choices of properties when there are nested entities
	public function serializeRecentComments(Iterable $recentComments): array
	{
		$result = [];

		foreach ($recentComments as $recentComment) {
			$result[] = [
				'text' => $recentComment->getText(),
				'articleTitle' => $recentComment->getArticle()->getTitle(),
				'creationDate' => $recentComment->getCreationDate()->format('Y-m-d H:i:s'),
			];
		}

		return $result;
	}
}
