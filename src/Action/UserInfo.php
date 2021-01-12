<?php

namespace App\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class UserInfo
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Request $request)
    {
        return $this->security->getUser();
    }
}
