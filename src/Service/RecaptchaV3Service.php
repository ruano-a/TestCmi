<?php 

namespace App\Service;

use ReCaptcha\ReCaptcha;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RecaptchaV3Service
{
    protected $requestStack;
    protected $secret;

    const THRESHOLD = 0.5;

    public function __construct($secret, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->secret       = $secret;
    }

    public function verify(string $action, string $gRecaptchaResponse, string $remoteIp = null)
    {
        $recaptcha = new ReCaptcha($this->secret);
        $recaptcha->setExpectedAction($action)
                  ->setScoreThreshold(self::THRESHOLD);

        $resp = $recaptcha->verify($gRecaptchaResponse);

        if ($resp->isSuccess()) {
            // Verified!
            return true;
        } 
        $errors = $resp->getErrorCodes();
        return false;
    }
}