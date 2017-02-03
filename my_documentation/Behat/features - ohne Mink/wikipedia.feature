# features/wikipedia.feature
Feature: search
  In order to see a word definition
  As a website user
  I need to be able to search for a word
  
  Scenario: Searching for a page that does exist
    Gegeben seien ich bin auf "https://en.wikipedia.org/wiki/Main_Page"
    Wenn ich gebe "Behavior Driven Development" in das Feld "search" ein
    Und ich drücke "searchButton"
    Dann sollte ich "agile software development" sehen
