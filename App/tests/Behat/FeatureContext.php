<?php

namespace Tests\Behat;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserBalance;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\MinkContext;
use Doctrine\ORM\EntityManagerInterface;

class FeatureContext extends MinkContext implements Context
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Session $session
    ) {}

    /**
     * @AfterScenario
     */
    public function clearDatabase(): void
    {
        $classModels = [
            User::class,
            Expense::class,
            Group::class,
            UserBalance::class
        ];

        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        foreach ($classModels as $classModel) {
            $tableName = $this->entityManager->getClassMetadata($classModel)->getTableName();

            $quotedTableName = '"' . $tableName . '"';

            $connection->executeStatement('ALTER TABLE ' . $quotedTableName . ' DISABLE TRIGGER ALL');

            $truncateSql = $platform->getTruncateTableSQL($quotedTableName, true);
            $connection->executeStatement($truncateSql);

            $connection->executeStatement('ALTER TABLE ' . $quotedTableName . ' ENABLE TRIGGER ALL');
        }
    }

    /**
     * @Given I am on \/signup
     */
    public function iAmOnSignup(): void
    {
        $this->visit('/signup');
    }

    /**
     * @Given I fill in the input Username with ":username"
     */
    public function iFillInTheInputUsernameWithUsername(string $username): void
    {
        $input = $this->getSession()->getPage()->find('named', array('field', "user_sign_up_username"));
        $input->setValue($username);
    }

    /**
     * @Given I fill in the input Email with :email
     */
    public function iFillInTheInputEmailWithEmail(string $email): void
    {
        $input = $this->getSession()->getPage()->find('named', array('field', "user_sign_up_email"));
        $input->setValue($email);
    }

    /**
     * @Given I fill in the input Password with :password
     */
    public function iFillInTheInputPasswordWithPassword(string $password): void
    {
        $input = $this->getSession()->getPage()->find('named', array('field', "user_sign_up_password_first"));
        $input->setValue($password);
    }

    /**
     * @Given I fill in the input Confirm password with :confirm_password
     */
    public function iFillInTheInputConfirmPasswordWithConfirmPassword(string $confirm_password): void
    {
        $input = $this->getSession()->getPage()->find('named', array('field', "user_sign_up_password_second"));
        $input->setValue($confirm_password);
    }

    /**
     * @When I submit the form
     */
    public function iSubmitTheForm(): void
    {
        $input = $this->getSession()->getPage()->find('named', array('button', "user_sign_up_save"));
        $input->click();
    }

    /**
     * @Then I should be redirect to :target_page
     */
    public function iShouldBeRedirectToTargetPage(string $target_page): void
    {
        if (stripos($this->getSession()->getCurrentUrl(), $target_page) === false) {
            throw new \Exception("Current URL is " . $this->getSession()->getCurrentUrl());
        }
    }

    /**
     * @Then I should stay on :arg1
     */
    public function iShouldStayOn($arg1)
    {
        if (stripos($this->getSession()->getCurrentUrl(), $arg1) === false) {
            throw new \Exception("Current URL is " . $this->getSession()->getCurrentUrl());
        }
    }


    /**
     * @Then I should see the message :message
     */
    public function iShouldSeeTheMessage(string $message): void
    {
        $fieldValue = $this->getSession()->getPage()->find('css', '.authentication_form_error > ul > li');
        if ($fieldValue->getText() != $message) {
            throw new \Exception("Message not found");
        }
    }

    /**
     * @Then I should see the flash message :message
     */
    public function iShouldSeeTheFlashMessage(string $message): void
    {
        $flashMessage = $this->getSession()->getPage()->find('css', '.flash-success');
        if ($flashMessage->getText() != $message) {
            throw new \Exception("Flash message not found");
        }
    }

    /**
     * @Given I visit :url
     */
    public function iVisit(string $url): void
    {
        $this->visit($url);
    }

    /**
     * @Then I should see the title in english :title
     */
    public function iShouldSeeTheTitleInEnglish(string $title): void
    {
        $title = $this->getSession()->getPage()->find('css', '.authentication_form_title');
        dump($title->getText());
        if ($title->getText() != "Sign Up") {
            throw new \Exception("Title not found");
        }
    }
}
