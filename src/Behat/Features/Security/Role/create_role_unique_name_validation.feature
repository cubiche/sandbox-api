@role1
Feature: Role unique name validation
  In order to avoid making mistakes when managing roles
  As an Administrator
  I want to be prevented from adding a new role with an existing name

  Background:
    Given there is a role "ACCOUNT_MANAGER"
    And I am logged in as an administrator

  Scenario: Adding a new role with used name
    Given I want to add a new role
    When I specify the name as "ACCOUNT_MANAGER"
    And I specify the permissions "app.account, app.strategy_focus"
    And I add it
    Then I should be notified that role with this name already exists
    And there should still be only one role with the name "ACCOUNT_MANAGER"
