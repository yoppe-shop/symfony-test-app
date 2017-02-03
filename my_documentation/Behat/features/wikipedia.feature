# features/wikipedia.feature
Feature: search
  In order to see a word definition
  As a website user
  I need to be able to search for a word
  
  @javascript
  Scenario: Searching for a page that does exist
    Given I am on "/wiki/Main_Page"
    When I fill in "search" with "Behavior Driv"
    Then I should see "Behavior Driven Development"

  Scenario: Searching for a page that does exist
    Given I am on "/wiki/Main_Page"
    When I fill in "search" with "Behavior Driven Development"
    And I press "searchButton"
    Then I should see "agile software development"
