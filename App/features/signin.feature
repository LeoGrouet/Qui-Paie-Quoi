Feature: signin
  In order to acces the application
  As a visitor user
  I need to be able to create an account

Scenario: Sign in the application
  Given I am on the signin page
  And I fill the input Username
  And I fill the input Email
  And I fill the input Password
  And I fill the input Confirm password
  When I click the submit button
  Then I should be redirect to login page
  Then I should see a flash message telling me my account was created