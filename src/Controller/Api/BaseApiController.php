<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Service\ApiSerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseApiController extends BaseController
{
	public function __construct(protected ApiSerializerInterface $serializer)
	{

	}

	protected function getResponse($data): JsonResponse
	{
		return new JsonResponse($data);
	}

	protected function serialize($data, string|array $groups = 'read')
	{
		return $this->serializer->serialize($data, $groups);
	}

	protected function serializedResponse($data, string|array $groups = 'read')
	{
		return $this->getResponse($this->serializer->serialize($data, $groups));
	}


	protected function serializedResponseOk($data = null, string|array $groups = 'read'): JsonResponse
	{
		if ($data)
			$data = $this->serialize($data, $groups);
		$result = ['result' => 'ok', 'data' => $data];

		return $this->getResponse($result);
	}

	protected function serializedResponseKo(string|bool|null $message = null): JsonResponse
	{
		$result = ['result' => 'ko', 'message' => $message ?? 'The operation failed'];

		return $this->getResponse($result);
	}
}
