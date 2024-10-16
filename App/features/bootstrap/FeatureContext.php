<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;

class FeatureContext implements Context
{

    private string $output;

    private function setOutput(string $output): void
    {
        $this->output = $output;
    }

    private function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @Given I am in a directory ":dir"
     */
    public function iAmInADirectory(string $dir): void
    {
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        chdir($dir);
    }

    /** @Given I have a file named ":file" */
    public function iHaveAFileNamed(string $file): void
    {
        touch($file);
    }

    /** @When I run ":command" */
    public function iRun(string $command): void
    {
        exec($command, $output);
        $this->setOutput(trim(implode("\n", $output)));
    }

    /** @Then I should get: */
    public function iShouldGet(PyStringNode $string): void
    {
        if ((string) $string !== $this->output) {
            throw new Exception(
                "Actual output is:\n" . $this->getOutput()
            );
        }
    }

    /**
     * @Given I send a request on :arg1
     */
    public function iSendARequestOn($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given I fill in :arg1::arg2
     */
    public function iFillIn($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I click the :arg1 button
     */
    public function iClickTheButton($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be redirect to :arg1
     */
    public function iShouldBeRedirectTo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see a flash message :arg1
     */
    public function iShouldSeeAFlashMessage($arg1)
    {
        throw new PendingException();
    }
}
