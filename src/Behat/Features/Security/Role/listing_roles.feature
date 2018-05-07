@role1
Feature: Listing the roles
  In order to know all the availables roles
  As an Administrator
  I want to see all the roles

  Background:
    Given there is a role "ADMIN" with the permissions "app"
    And there is a role "SALES_MANAGER"
    And I am logged in as an administrator

  Scenario: Listing roles
    Given I want to see all the roles
    When I list its
    Then I should see 2 roles
