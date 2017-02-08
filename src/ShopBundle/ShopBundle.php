<?php

namespace ShopBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ShopBundle extends Bundle
{
    public function __construct()
    {
        if (!defined("DIR_SEP")) define("DIR_SEP", "\\");
    }
}
