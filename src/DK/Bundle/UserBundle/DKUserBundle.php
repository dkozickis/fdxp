<?php

namespace DK\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DKUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
