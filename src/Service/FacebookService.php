<?php

namespace App\Service;

use App\Entity\User;
use App\Service\UrlRequesterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class FacebookService
{
	const USER_TYPE = 'fb';

	public function __construct(protected EntityManagerInterface $entityManager, protected UrlRequesterInterface $urlRequester, protected Security $security)
	{

	}

	public function login(array $userData): string|bool
	{
		$url = 'https://graph.facebook.com/' . $userData['userID'] . '?access_token=' . $userData['accessToken'];
		$data = json_decode($this->urlRequester->get($url), true);
		if (isset($data['error']) || !isset($data['name']) || !isset($data['id']))
		{
			return $data['error']['message'] ?? false;
		}

		$user = $this->entityManager->getRepository(User::class)->findOneBy([
			'type' 			=> self::USER_TYPE,
			'externalId' 	=> $data['id']
		]);
		if (!$user) {
			$user = new User();

			$user->setUsername($data['name']);
			$user->setExternalId($data['id']);
			$user->setType(self::USER_TYPE);
			$this->entityManager->persist($user);
		}
		else {
			if ($user->getUsername() !== $data['name']) {
				$user->setUsername($data['name']);
			}
		}
		$this->entityManager->flush();
		$this->security->login($user);

		return true;
	}
}
