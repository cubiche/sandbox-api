@authentication
Feature: Sign out from the application
  In order to exit my environment dashboard
  As a User
  I want to be able to log out from the application

  Background:
    Given I am logged in as "ivan@cubiche.com"

  Scenario: Sign out
    Given  I want to log out
    When I log out
    Then I should be logged out