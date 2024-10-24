Feature: Signup
  In order to acces the application
  As a visitor user
  I need to be able to create an account

  Background:
    Given I am on /signup

  Scenario Outline: Sign-up the application failed
    And I fill in the input Username with "<username>"
    And I fill in the input Email with "<email>"
    And I fill in the input Password with "<password>"
    And I fill in the input Confirm password with "<confirm_password>"
    When I submit the form
    Then I should stay on "/signup"
    And I should see the message "<message>"
    

  Examples:
  | username | email                | password        | confirm_password | error_input | message |
  | leo      | test.test@gmail.com  | Testpassword/10 | Testpassword/10  | Username    | Nom d'utilisateur trop court ( Minimum 4 caractères) .|
  | Leoooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo      | test.test@gmail.com  | Testpassword/10 | Testpassword/10  | Username    | Nom d'utilisateur trop long ( Max 64 caractères) .|
  |   " "    | test.test@gmail.com  | Testpassword/10 | Testpassword/10  | Username    | Veuillez entrer un nom d'utilisateur .|
  | leooooo  | test.test@gmail      | Testpassword/10 | Testpassword/10  | Email       | Veuillez entrer un email valide.|
  | leooooo  | ttttttttttteeeeeeeeeeeeeeesssssssssssssssssttttttttttttttttttttttttttteeeeeeeeeeessssssssssssssssstttttttttttttttttttttttttttttteeeeeeeeeeeeeeeeeessssssssssssssssstttttttttttttttttttttttttttttteeeeeeeeeeeeeeeeeessssssssssssssssstttttttttttttttttttttttttttttteeeeeeeeeeeeeeeeeesssssssssssssssssttttttttttttttttttttttt@gmail.com | Testpassword/10 | Testpassword/10  | Email       | Votre email est trop long ( Max 320 caractères) .|
  | leooooo  | test.test@gmail.com  | password        | password         | Password    | Mot de passe trop faible (Au moins 1 majuscule, minuscule, chiffre et caractère spéciaux ).|
  | leooooo  | test.test@gmail.com  | Testpassword/10 | password         | Confirm password | Les valeurs ne correspondent pas. |

  Scenario Outline: Should display flash message
    And I fill in the input Username with "<username>"
    And I fill in the input Email with "<email>"
    And I fill in the input Password with "<password>"
    And I fill in the input Confirm password with "<confirm_password>"
    When I submit the form
    Then I should be redirect to "/signin"
    And I should see the flash message "Félicitations ! Votre compte a bien été créé."

    Examples:
  | username | email                | password        | confirm_password |
  | testing  | test.test@gmail.com  | Testpassword/10 | Testpassword/10  |

  Scenario:
    And I visit "/en/signup"
    Then I should see the title in english "Sign Up"
  