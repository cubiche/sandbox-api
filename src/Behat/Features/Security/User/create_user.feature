@user
Feature: Create user
  In order to manage my users
  As an Administrator
  I want to create users

  Background:
    Given I am logged in as an administrator

  Scenario: Create a user
    Given I want to create a user
    When I specify the username as "ivan"
    When I specify the email as "ivan@cubiche.com"
    And I specify the password as "ivanoff"
    And I create it
    Then the user will be created