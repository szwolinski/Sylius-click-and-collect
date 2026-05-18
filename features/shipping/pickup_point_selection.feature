@shipping
Feature: Selecting a pickup point during checkout
    In order to collect my order personally
    As a Customer
    I want to be able to choose a specific physical store during checkout

    Background:
        Given the store operates on a single channel in "United States"
        And the store offers a "Click & Collect" shipping method with "$5.00" fee and free above "$100.00"
        And this shipping method has pickup points named "California Store" and "New York Store"
        And I am a logged in customer

    @ui
    Scenario: Successfully selecting z California Store pickup point and saving it in the shipment
        Given the store has a product "T-shirt" priced at "$15.00"
        And I added product "T-shirt" to the cart
        When I proceed with selecting "Click & Collect" shipping method
        And I select "California Store" as my pickup point
        And I complete the shipping step
        Then my shipment pickup point should be "California Store"

    @ui
    Scenario: Successfully selecting z New York Store pickup point and saving it in the shipment
        Given the store has a product "T-shirt" priced at "$15.00"
        And I added product "T-shirt" to the cart
        When I proceed with selecting "Click & Collect" shipping method
        And I select "New York Store" as my pickup point
        And I complete the shipping step
        Then my shipment pickup point should be "New York Store"
