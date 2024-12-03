# Features

* This API application has the following features & endpoints
    * GET request to fetch all customers 
        (optionally with their associated invoices)
    * GET request to fetch a single customer by id
    * GET all invoinces
    * GET params provided for filtering the results
    * Sanctum middleware protection of routes for token-based requests


# How to run the application

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

* Add a new customer

    * endpoint:
        * http://127.0.0.1:8000/api/v1/customers/

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
    * endpoint:
        * http://127.0.0.1:8000/api/v1/customers/327

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
    * endpoint:
        * http://127.0.0.1:8000/api/v1/customers/327

    * Payload
        * {
            "id": 327,
            "name": "Jim Carey King",
            "type": "B",
            "state": "New York",
         }

    
# POST Bulk Insert Request

* Allow clients to submit bulk inserts of multiple invoices
    * endpoint:
        * http://127.0.0.1:8000/api/v1/invoices/bulk

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


# GET Filtering of requests

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
