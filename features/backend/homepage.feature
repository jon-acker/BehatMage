Feature: Index Page
  As a website administrator
  I want to see the index page
  So that I can understand what the website offers and how it can benefit me

  Scenario: Viewing the dashboard
    Given I am logged in as admin user "admin-test" identified by "123123pass"
     When I open admin URI "admin/dashboard/index"
     Then I should be on the dashboard page


  Scenario: Viewing the process list
    Given I am logged in as admin user "admin-test" identified by "123123pass"
    When I open admin URI "admin/process/list"
    Then I should be on the process list page