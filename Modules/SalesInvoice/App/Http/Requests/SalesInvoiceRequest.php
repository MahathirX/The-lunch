<?php

namespace Modules\SalesInvoice\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesInvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'create_date'      => ['required', 'date', 'date_format:Y-m-d'],
            'due_date'         => ['nullable', 'date', 'date_format:Y-m-d'],
            'customer_phone'   => ['required', 'string', 'min:10', 'max:15', 'regex:/^\+?[0-9]{10,15}$/'],
            'customer_name'    => ['required', 'string', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'tax'              => ['nullable', 'numeric', 'min:0'],
            'paid_amount'      => ['nullable', 'numeric', 'min:0'],
            'attachment'       => ['nullable'],
            'discount'         => ['nullable', 'numeric', 'min:0'],
            'previous_due'     => ['nullable', 'numeric', 'min:0'],
        ];
    }

/**
 * Determine if the user is authorized to make this request.
 */
public function authorize(): bool
{
    return true;
}

}
