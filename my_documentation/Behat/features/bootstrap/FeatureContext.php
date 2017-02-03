<?php

use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Behat context class.
 */
class FeatureContext extends RawMinkContext
{
    public function doSomething()
    {
        $session = $this->getSession();
        $session->visit('https://en.wikipedia.org');
    }
}
