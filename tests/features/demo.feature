# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
  In order to prove that the Behat Symfony extension is correctly installed
  As a user
  I want to have a demo scenario

  Scenario: Listar producto
    Given a product with the name ChechuFlix
    And a product with the name Sergio
    And a product
    When I send a "GET" request to "/product"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be valid according to the schema "tests/Functional/Schemas/Products.json"
    And the response json should contains 3 elements

  Scenario: Listar x productos
    Given a 5 of product
    When I send a "GET" request to "/product"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be valid according to the schema "tests/Functional/Schemas/Products.json"
    And the response json should contains 5 elements

  Scenario: Crear un producto
    Given a category with id "7d4639ee-860c-455e-a2e9-cf1e7ef6d336"
    When I send a "POST" request to "/product" with body:
    """
      {
         "id": "5130d49d-5e90-4b44-870c-fbbe9ba24121",
         "category_id": "7d4639ee-860c-455e-a2e9-cf1e7ef6d336",
         "name": "Alberto",
         "description": "Demo"
      }
    """
    Then the response status code should be 201
    And the product with id "5130d49d-5e90-4b44-870c-fbbe9ba24121" is created


