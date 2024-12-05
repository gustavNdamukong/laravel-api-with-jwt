# Features

## Laravel version: 11
* This API application has the following features & endpoints


    * POST request to signup a new user

    * POST request to login a new user

    * POST request to logout a user (secured route)

    * POST request to refresh the user's access token

    * GET request to fetch all customers 

        (optionally with their associated invoices)

    * GET request to fetch a single customer by id

    * GET all invoinces

    * POST request to create a new customer (secured route)

    * PUT request to replace an existing customer (secured route)

    * PATCH request to modify some fields of an existing customer (secured route)

    * GET params provided for filtering the results

    * Sanctum middleware protection of routes for token-based request authentication

    * SwaggerUI (Open API) documentation


# How to start the application

Run the migrations and seeds in one go

    php artisan migrate --seed

If you have no other local deployment solution vailable, just run

    php artisan serve

The application will then be available on:

    http://127.0.0.1:8000


# Setup

    Before you start, generate tokens for the various request endpoints 
    by visiting:

    http://127.0.0.1:8000/setup

    This will create three access tokens and return them on screen. 
    Copy and paste them somewhere for testing eg in Postman via Authorization headers.
    
* The three tokens and their abilities are:

    * Admin ('create', 'update', 'delete')
    * Update ('create', 'update')
    * Basic ('none')


# Testing the routes (for example with Postman)

## GET requests

* View all customers

    * http://127.0.0.1:8000/api/v1/customers/

* View a single customer

    * http://127.0.0.1:8000/api/v1/customers/327

* View all invoices

    * http://127.0.0.1:8000/api/v1/invoices/


# POST Requests

* Users 
    * Sign up a new user

        * endpoint:
            * http://127.0.0.1:8000/api/v1/signup/

        * Body payload:

            * {
                "name": "John Doe",
    			"email": "johndoe@example.com",
    			"password": "password",
    			"password_confirmation": "password"
             }


    * Login a user

        * endpoint:
            * http://127.0.0.1:8000/api/v1/login/

        * Body payload:

            * {
                "email": "johndoe@example.com",
        		"password": "password"
             }


    * Logout a user (secured route)

        * endpoint:
            * http://127.0.0.1:8000/api/v1/logout/

        * Authorization Bearer Token: 10|EBFRNw1F5qtko0tKMDQVcBXlBfc5d8bmMC...


    * Refresh user's access token eg so you can keep them logged in. 

        * endpoint:
            * http://127.0.0.1:8000/api/v1/refresh_token/

        * Body payload:

            * {
                "refresh_token": "bZfyZLv1LmIBySXyCp5t2lPeN8y7slqDqETI8B...
             }


    * Add a new customer (secured route)

        * endpoint:
            * http://127.0.0.1:8000/api/v1/customers/

        * Authorization Bearer Token: 10|EBFRNw1F5qtko0tKMDQVcBXlBfc5d8bmMC...

        * Body payload:

            * {
                "name": "Jim Carey",
                "type": "I",
                "email": "jimmyfn@carey.com",
                "address": "235 Langley Street",
                "city": "Queens",
                "state": "New York",
                "postalCode": "234654"
            }

        * If you would rather use the CLI

            curl -X POST  http://127.0.0.1:8000/api/v1/customers \
            -H "Content-Type: application/json" \
            -H "Accept: application/json" \
            -d '{
                    "name": "Jim Carey",
                    "type": "I",
                    "email": "jimmyfn@carey.com",
                    "address": "235 Langley Street",
                    "city": "Queens",
                    "state": "New York",
                    "postalCode": "234654"
                }'

# PUT Requests

* Update (replace) an existing customer (Need to provide data for all fields)
    (secured route)
    * endpoint:
        * http://127.0.0.1:8000/api/v1/customers/327

    * Authorization Bearer Token: 10|EBFRNw1F5qtko0tKMDQVcBXlBfc5d8bmMC...

    * Payload
        * {
            "id": 327,
            "name": "Jim Carey King",
            "type": "B",
            "email": "jimmyfn@carey.com",
            "address": "200 Langley Street",
            "city": "Queens",
            "state": "New York",
            "postalCode": "234654"
         }


# PATCH Requests

* Update an existing customer (no need to provide data for all fields)
    (secured route)
    * endpoint:
        * http://127.0.0.1:8000/api/v1/customers/327

    * Authorization Bearer Token: 10|EBFRNw1F5qtko0tKMDQVcBXlBfc5d8bmMC...

    * Payload
        * {
            "id": 327,
            "name": "Jim Carey King",
            "type": "B",
            "state": "New York",
         }

    
# POST Bulk Insert Request

* Allow clients to submit bulk inserts of multiple invoices
    (secured route)
    * endpoint:
        * http://127.0.0.1:8000/api/v1/invoices/bulk

    * Authorization Bearer Token: 10|EBFRNw1F5qtko0tKMDQVcBXlBfc5d8bmMC...

    * Payload
        * [{
                "customerId": 1,
                "amount": 13642,
                "status": "P",
                "billedDate": "2019-01-23 10:35:25",
                "paidDate": "2015-04-08 00:45:27"
			},
            {
                "customerId": 1,
                "amount": 16710,
                "status": "V",
   	 			"billedDate": "2016-12-03 01:14:18",
    			"paidDate": null
			}]


# GET Request Filtering Options

* Allow clients to use query string parameters to filter request results
    Note that you can combine multiple filters, separated by ampersand (&)

    * View all customers with their associated invoices

        * http://127.0.0.1:8000/api/v1/customers?includeInvoices=true


    * View only customers who are Businesses (type of 'B')

        * http://127.0.0.1:8000/api/v1/customers?type[eq]=B


    * View only customers who are Businesses (type of 'I')

        * http://127.0.0.1:8000/api/v1/customers?type[eq]=I


    * Viewonly customers whose post code is greater than 30000

        * http://127.0.0.1:8000/api/v1/customers?postCode[gt]=30000


    * View only customers who are resident in London, and who are Businesses

        * http://127.0.0.1:8000/api/v1/customers?state[eq]=London&type[eq]=B


# SwaggerUI (Open API) documentation

This project implements the darkaonline/l5-swagger Laravel package for Swagger 
UI documentation. To start you off documenting your API, i have added SwaggerUI 
annotations for 3 routes.

I have also included the complete Postman collection for this project. It's in 
the json file in the root directory named: "customers-and-orders-api.postman_collection.json"

![Swagger UI docs](https://github.com/gustavNdamukong/laravel-api-with-jwt/blob/main/public/images/swaggerUI-img.png?raw=true)
