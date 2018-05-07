@role1
Feature: Role validation
  In order to avoid making mistakes when managing roles
  As an Administrator
  I want to be prevented from adding it without specifying required fields

  Background:
    Given I am logged in as an administrator

  Scenario: Adding a new role without specifying a name
    Given I want to add a new role
    When I specify the permissions "app.business_cycle, app.account, app.strategy_focus"
    And I add it
    Then I should be notified that the name is required
