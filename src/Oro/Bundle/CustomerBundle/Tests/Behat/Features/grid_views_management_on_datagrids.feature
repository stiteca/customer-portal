@ticket-BAP-17827
@ticket-BB-15402
@fixture-OroLocaleBundle:ZuluLocalization.yml
@fixture-OroCustomerBundle:CustomerUserAddressFixture.yml
Feature: grid views management on datagrids
  In order to manage grid views on front store
  As Frontend User
  I need to create and use grid view on some grid

  Scenario: Create new default grid view with changed filter
    Given I signed in as AmandaRCole@example.org on the store frontend
    And I click "Account"
    And I click "Address Book"
    And I hide filter "State" in "Customer Company Addresses Grid" grid
    And I hide column "State" in frontend grid
    When I click grid view list on "Customer Company Addresses Grid" grid
    And I click "Save As New"
    And I set "Test view" as grid view name for "Customer Company Addresses Grid" grid on frontend
    And I mark Set as Default on grid view for "Customer Company Addresses Grid" grid on frontend
    And I click "Add"
    And I should see "View has been successfully created" flash message
    And I click "Flash Message Close Button"
    Then I should see a "Customer Company User Addresses Grid View List" element

  Scenario: Check that new grid view is used as default
    When I click "Account"
    And I click "Address Book"
    Then I should see "Test view"
    And shouldn't see "State" column in "Customer Company Addresses Grid" frontend grid
    And I shouldn't see "State" filter in frontend grid

  Scenario: Gridview can be renamed few times
    When I click grid view list on "Customer Company Addresses Grid" grid
    And I click "Rename"
    And I set "Test view 01" as grid view name for "Customer Company Addresses Grid" grid on frontend
    And I click "Save"
    And I should see "View has been successfully updated" flash message
    And I click "Flash Message Close Button"
    Then I should see "Test view 01"
    When I click grid view list on "Customer Company Addresses Grid" grid
    And I click "Rename"
    And I set "Test view 02" as grid view name for "Customer Company Addresses Grid" grid on frontend
    And I click "Save"
    And I should see "View has been successfully updated" flash message
    Then I should see "Test view 02"

  Scenario: Add translation for Saved Views grid action
    Given I login as administrator
    And I enable the existing localizations
    And I go to System / Configuration
    And go to System/Localization/Translations
    And filter Translated Value as is empty
    And filter English translation as contains "Saved Views"
    When I edit "oro_frontend.datagrid_views.saved_views" Translated Value as "Saved Views - Zulu"
    Then I should see following records in grid:
      | Saved Views - Zulu |
    When I click "Update Cache"
    Then I should see "Translation Cache has been updated" flash message

  Scenario: Check translations for grid view list
    Given I signed in as AmandaRCole@example.org on the store frontend
    And I click "Account"
    And I click "Address Book"
    And I click "Localization Switcher"
    And I select "Zulu" localization
    When I click grid view list on "Customer Company Addresses Grid" grid
    Then I should see "Saved Views - Zulu"
