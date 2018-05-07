@user
Feature: Disable user
  In order to manage my users
  As an Administrator
  I want to disable users

  Background:
    Given I am logged in as an administrator
    And there is a user "ivan@cubiche.com" identified by "ivanoff"

  Scenario: Disable a user
    Given I want to disable a user
    When I specify the user as "ivan@cubiche.com" to disable it
    And I disable it
    Then the user will be disabled