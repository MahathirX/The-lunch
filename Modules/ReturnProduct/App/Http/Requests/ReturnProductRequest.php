<?php

namespace Modules\ReturnProduct\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'search_invoices_number' => ['required'],
            'customer_id'            => ['required'],
            'create_date'            => ['required'],
            'note'                   => ['required'],
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
