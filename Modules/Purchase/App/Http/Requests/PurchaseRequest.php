<?php

namespace Modules\Purchase\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'productids'    => ['required', 'array', 'min:1'],
            'productids.*'  => ['required'],
            'supplier_id'   => ['required'],
            'invoice_date'  => ['required'],
            'purchase_type' => ['required'],
            'status'        => ['required'],
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
