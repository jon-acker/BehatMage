Feature: Index Page
  As a website administrator
  I want to see the index page
  So that I can understand what the website offers and how it can benefit me

  Scenario: Display Header
    Given I am logged in as an admin
    When I open admin URI "/admin/dashboard"
    Then I should see text "Index Management"
