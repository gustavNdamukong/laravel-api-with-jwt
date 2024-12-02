<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Requests\v1\StoreCustomerRequest;
use App\Http\Requests\v1\UpdateCustomerRequest;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CustomerResource;
use App\Http\Resources\v1\CustomerCollection;
use App\Filters\v1\CustomersFilter;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /*
        {
            "name": "Gustav Ndamukong",
            "type": "B",
            "email": "gustavfn@yahoo.co.uk",
            "address": "235 Langley Street",
            "city": "Toronto",
            "state": "Ontario",
            "postalCode": "234654"
        }
        */

        /*
        curl -X POST http://localhost:8888/laravel-api-with-jwt/public/api/v1/customers \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d '{
            "name": "Gustav Ndamukong",
            "type": "B",
            "email": "gustavfn@yahoo.co.uk",
            "address": "235 Langley Street",
            "city": "Toronto",
            "state": "Ontario",
            "postalCode": "234654"
        }'

        */



        $filter = new CustomersFilter();
        $filterItems = $filter->transform($request); // [['fieldName', 'operator', 'value']]

        $includeInvoices = $request->query('includeInvoices');
        $customers = Customer::where($filterItems);

        // check if the user wants to include invoices
        if ($includeInvoices) {
            // NOTES: here's how you grab records from a model with all child related records
            //  call with('childName') on the model
            $customers = $customers->with('invoices');
            // return new CustomerCollection(Customer::paginate());
        }

        // That's how to persist the existing query string across page reloads.
        // Call appends() on paginate(), & pass it the current query string ($request->query())
        return new CustomerCollection($customers->paginate()->appends($request->query()));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //return $customer;
        $includeInvoices = request()->query('includeInvoices');
        if ($includeInvoices) {
            // Notes: lazy-load invoices of customer after the fact-coz $customer was already sent
            //  to the show() method with no invoices
            return new CustomerResource($customer->loadMissing('invoices'));
        }

        return new CustomerResource($customer);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
