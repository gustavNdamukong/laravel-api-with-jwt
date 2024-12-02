<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /*
         The '*.' is Laravel's way for u to specify that the validation applies to multiple objects
              that will be sent in one single array. If the data structure looked like this:

            data: [
                {...},
                {...},
                etc
            ]
            then your validation rules keys will reflect that data structure like so:
                'data.*.customerId => ...
                'data.*.amount => ...
                etc
        */
        return [
            '*.customerId' => ['required', 'integer'], //we need to pass the id of the associated customer
            '*.amount' => ['required', 'numeric'],
            '*.status' => ['required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
            '*.billedDate' => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paidDate' => ['date_format:Y-m-d H:i:s', 'nullable'], // paid data can be null, if the invoice is not paid
        ];
    }

    /**
     * This method allows us to merge in other values
     * @return void
     */
    protected function prepareForValidation() {
        // update this to apply to all the individual objects submitted in an array
        $data = [];

        foreach ($this->toArray() as $obj) {
            $obj['customer_id'] = $obj['customerId'] ?? null;
            $obj['billed_date'] = $obj['billedDate'] ?? null;
            $obj['paid_date'] = $obj['paidDate'] ?? null;

            $data[] = $obj;
        }

        $this->merge($data);
    }
}
