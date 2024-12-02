<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Requests\v1\StoreInvoiceRequest;
use App\Http\Requests\v1\UpdateInvoiceRequest;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\InvoiceResource;
use App\Http\Resources\v1\InvoiceCollection;
use App\Filters\v1\InvoicesFilter;
use Illuminate\Support\Arr;
use App\Http\Requests\v1\BulkStoreInvoiceRequest;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new InvoicesFilter();
        $filterItems = $filter->transform($request); // [['fieldName', 'operator', 'value']]

        // check if $queryItems is an empty array
        if (count($filterItems) == 0) {
            return new InvoiceCollection(Invoice::paginate());
        } else {
            $invoices = Invoice::where($filterItems)->paginate();

            // That's how to persist the existing query string across page reloads.
            // Call appends() on paginate(), & pass it the current querystring ($request->query())
            return new InvoiceCollection($invoices->appends($request->query()));
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        //
    }

    public function bulkStore(BulkStoreInvoiceRequest $request)
    {
        // we convert the submitted data into a collection coz a collection is easier to work with
        //  for example, it has the map() function
        $bulk = collect($request->all())->map(function($arr, $key) {
            // we use the helper function Arr in this closure to say out of that data we need
            // to store everything else in the DB except these 3 fields:
            // 'customerId', 'billedDate', 'paidDate'
            return Arr::except($arr, ['customerId', 'billedDate', 'paidDate']);
        });

        // we can only pass an array to insert() not a collection, so we re-convert to an array
        Invoice::insert($bulk->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
