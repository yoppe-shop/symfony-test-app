<?php

namespace ShopBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ShopBundle extends Bundle
{
    public function __construct()
    {
        define("DIR_SEP", "\\");
    }
}
