@tut
Feature: Simple calculator!

  Scenario: Adding two numbers (1+1)
    When I visit "/add/1/1"
    Then I should see "2"

  Scenario: Adding two numbers (12+13)
    When I visit "/add/12/13"
    Then I should see "25"

  Scenario Outline: Checking multiple operations in one scenario
    When I visit "/add/<num1>/<num2>"
    Then I should see "<result>"

    Examples:
        |num1|num2|result|
        |5|100|105|
        |0|4|4|
        |50|2|52|
        |0|1|1|

  Scenario: instead of a negative result the system returns zero
    When I visit "/add/-5/3"
    Then I should see "0"