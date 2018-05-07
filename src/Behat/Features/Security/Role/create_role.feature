@role1
Feature: Adding a new role
  In order to categorize my users by roles
  As an Administrator
  I want to add a new role to the environment

  Background:
    Given I am logged in as an administrator

  Scenario: Adding a new role
    Given I want to add a new role
    When I specify the name as "SALES_MANAGER"
    And I specify the permissions "app.business_cycle, app.account, app.strategy_focus"
    And I add it
    Then I should be notified that it has been successfully created
    And this role should appear in the list
