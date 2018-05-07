@authentication
Feature: Sign in to the application
  In order to access my environment dashboard
  As a Visitor
  I want to be able to log in to the application

  Background:
    Given there is a user "ivan@cubiche.com" identified by "ivanoff"

  Scenario: Sign in with email and password
    Given I want to log in
    When I specify the username as "ivan@cubiche.com"
    And I specify the password as "ivanoff"
    And I log in
    Then I should be logged in

  Scenario: Sign in with an invalid user
    Given I want to log in
    When I specify the username as "admin@example.com"
    And I specify the password as "pswd"
    And I log in
    Then I should be notified that "admin@example.com" doesn't exist
    And I should not be logged in

  Scenario: Sign in with bad credentials
    Given I want to log in
    When I specify the username as "ivan@cubiche.com"
    And I specify the password as "pass"
    And I log in
    Then I should be notified about bad credentials
    And I should not be logged in