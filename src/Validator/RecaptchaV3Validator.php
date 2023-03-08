<?php

namespace App\Validator;

use App\Service\RecaptchaV3Service;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\RequestStack;

class RecaptchaV3Validator extends ConstraintValidator
{
	protected $recaptchaV3Service;
    protected $requestStack;

	public function __construct(RecaptchaV3Service $recaptchaV3Service, RequestStack $requestStack)
	{
		$this->recaptchaV3Service = $recaptchaV3Service;
        $this->requestStack       = $requestStack;
	}

    public function validate($value, Constraint $constraint)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$this->recaptchaV3Service->verify($constraint->action, $value, $request->getClientIp()))
        	$this->context->buildViolation($constraint->message)
                ->addViolation();
    }
}