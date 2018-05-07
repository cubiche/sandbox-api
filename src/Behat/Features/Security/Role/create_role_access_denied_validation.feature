@role1
Feature: Role access denied validation
  In order to avoid managing roles
  As a non-administrator user
  I want to be prevented from adding it without required access

  Background:
    Given there is a role "GUEST" with the permissions "app.role.list, app.role.view"
    And there is a user "ivan@cubiche.com" identified by "pass" with the role "GUEST"
    And I am logged in as "ivan@cubiche.com"

  Scenario: Adding a new role with invalid credentials
    Given I want to add a new role
    When I specify the name as "SALES_MANAGER"
    And I specify the permissions "app.business_cycle, app.account, app.strategy_focus"
    And I add it
    Then I should be notified that I don't have permission to do it
