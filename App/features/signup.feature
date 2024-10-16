Feature: Signup
  In order to acces the application
  As a visitor user
  I need to be able to create an account

Scenario: Sign-up the application
  Given I send a request on "/signup"
  And I fill in "Username":"Leo"
  And I fill in "Email":"leo.grouet@gmail.com"
  And I fill in "Password":"password"
  And I fill in "ConfirmPassword":"password"
  When I click the "Submit" button
  Then I should be redirect to "/signin"
  And I should see a flash message "Congratulations! Your account has been created successfully."