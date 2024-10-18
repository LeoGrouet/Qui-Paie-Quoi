Feature: Signup
  In order to acces the application
  As a visitor user
  I need to be able to create an account

  Scenario: Sign-up the application
    Given I am on /signup
    And I fill in the input "Username" with Leo
    And I fill in the input "Email" with leo.grouet@gmail.com
    And I fill in the input "Password" with password
    And I fill in the input "Confirm password" with password
    When I submit the form
    Then I should be redirect to "/signin"
    And I should see a flash message "Congratulations! Your account has been created successfully."
