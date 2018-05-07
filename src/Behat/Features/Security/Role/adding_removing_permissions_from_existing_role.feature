@role
Feature: Adding/removing permissions from an existing role
  In order to extend a role
  As an Administrator
  I want to be able to add or remove permissions from an existing role

  Background:
    Given there is a role "ACCOUNT_MANAGER" with the permissions "app.role, app.role.create"
    And I am logged in as an administrator

  Scenario: Adding permissions to an existing role
    Given I want to modify the "ACCOUNT_MANAGER" role
    When I specify the permissions "app.role.edit, app.role.view" to add its
    And I add the permissions
    Then I should be notified that the permissions have been successfully added
    And this role should have the "app.role, app.role.create, app.role.edit, app.role.view" permissions

  Scenario: Removing permissions from an existing role
    Given I want to modify the "ACCOUNT_MANAGER" role
    When I specify the permissions "app.role, app.role.create" to remove its
    And I remove the permissions
    Then I should be notified that the permissions have been successfully removed
    And this role should not have the "app.role, app.role.create" permissions
