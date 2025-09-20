<?php

namespace Modules\Supplier\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name'          => ['required'],
            'company_name'  => ['required'],
            'phone'         => ['required', 'unique:suppliers,phone'],
            'email'         => ['nullable', 'unique:suppliers,email'],
            'address'       => ['required'],
            'status'        => ['required'],
        ];
    
        if (request()->update_id) {
            $updateId = request()->update_id;
            $rules['phone'] = ['required', Rule::unique('suppliers', 'phone')->ignore($updateId)];
            $rules['email'] = ['nullable', Rule::unique('suppliers', 'email')->ignore($updateId)];
        }
    
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
