<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /*
    public function doSomething()
    {
        $session = $this->getSession();
        $session->visit('http://behat.org');
    }
    */
    /**
     * @Given I have a file named :file
     */
    public function iHaveAFileNamed($file)
    {
        touch($file);
    }

    /**
     * @Given I have a directory named :dir
     */
    public function iHaveADirectoryNamed($dir)
    {
        mkdir($dir);
    }

    /**
     * @When I run :command
     */
    public function iRun($command)
    {
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }

    /**
     * @Then I should see :string in the output
     */
    public function iShouldSeeInTheOutput($string)
    {
        if (strpos($this->output, $string) === false) {
            throw new \Exception('Did not see' . $string);
        }
    }

    public function __construct()
    {
    }
}
