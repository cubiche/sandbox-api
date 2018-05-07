@user
Feature: Enable user
  In order to manage my users
  As an Administrator
  I want to enable users

  Background:
    Given I am logged in as an administrator
    And there is a disabled user "ivan@cubiche.com" identified by "ivanoff"

  Scenario: Enable a user
    Given I want to enable a user
    When I specify the user as "ivan@cubiche.com" to enable it
    And I enable it
    Then the user will be enabled