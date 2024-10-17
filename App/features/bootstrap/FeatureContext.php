<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use DMore\ChromeDriver\ChromeDriver;
use Symfony\Component\Panther\PantherTestCase;

class FeatureContext extends PantherTestCase implements Context
{
    #[Given("I am on /signup")]
    public function iAmOnSignup()
    {
        $mink = new Mink(array(
            'browser' => new Session(new ChromeDriver('http://localhost:9222', null, 'http://www.google.com'))
        ));
    }

    #[Given('I fill in the input "Username" with Leo')]
    public function iFillInTheInputUsernameLeo()
    {
        throw new PendingException();
    }

    #[Given('I fill in the input "Email" with leo.grouet@gmail.com')]
    public function iFillInTheInputEmailLeoGrouetGmailCom()
    {
        throw new PendingException();
    }

    #[Given('I fill in the input "Password" with password')]
    public function iFillInTheInputPasswordPassword()
    {
        throw new PendingException();
    }

    #[Given('I fill in the input "Confirm password" with password')]
    public function iFillInTheInputConfirmpasswordPassword()
    {
        throw new PendingException();
    }

    #[When("I submit the form")]
    public function iSubmitTheForm()
    {
        throw new PendingException();
    }

   #[Then('Then I should be redirect to ":arg1"')]
    public function iShouldBeRedirectTo($arg1)
    {
        throw new PendingException();
    }

   #[Then('I should see a flash message ":arg1"')]
    public function iShouldSeeAFlashMessage($arg1)
    {
        throw new PendingException();
    }
}
