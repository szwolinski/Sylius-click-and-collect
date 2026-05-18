@shipping
Feature: Click & collect shipping pricing calculation
    In order to pay the correct amount for my delivery
    As a Customer
    I want my shipping fees to be calculated accurately based on my order value

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "T-shirt" priced at "$15.00"
        And the store has a product "Premium Jacket" priced at "$120.00"
        And the store offers a "Click & Collect" shipping method with "$5.00" fee and free above "$100.00"
        And this shipping method has pickup points named "California Store" and "New York Store"

    @ui
    Scenario: Charging a standard shipping fee for orders below the threshold
        Given I added product "T-shirt" to the cart
        And I addressed the cart to "United States"
        When I want to complete the shipping step
        Then I should be on the checkout shipping step
        And I should see shipping method "Click & Collect" with fee "$5.00"

    @ui
    Scenario: Granting free shipping for orders above the threshold
        Given I added product "Premium Jacket" to the cart
        And I addressed the cart to "United States"
        When I want to complete the shipping step
        Then I should be on the checkout shipping step
        And I should see shipping method "Click & Collect" with fee "$0.00"
