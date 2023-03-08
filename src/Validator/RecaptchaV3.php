<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RecaptchaV3 extends Constraint
{
    public $message = 'action.only.for.humans';
    public $action;

    public function __construct(string $action, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->action = $action;
    }
}