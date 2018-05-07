@authentication
Feature: Sign in to the application
  In order to access my environment dashboard
  As a Visitor
  I can not be able to log in to the application with a deactivated user

  Background:
    Given there is a disabled user "ivan@cubiche.com" identified by "ivanoff"

  Scenario: Sign in with email and password to desactivated user
    Given I want to log in
    When I specify the username as "ivan@cubiche.com"
    And I specify the password as "ivanoff"
    And I log in
    Then I should be notified that "ivan@cubiche.com" doesn't exist
    And I should not be logged in