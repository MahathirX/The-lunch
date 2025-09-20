<?php

namespace Modules\ExpenseList\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'create_date'         => ['required'],
            'expense_category_id' => ['required'],
            'amount'              => ['required'],
            'status'              => ['required'],

        ];
        if($request->is_Method('PUT')){
            return [
                'create_date'         => ['nullable'],
                'expense_category_id' => ['nullable'],
                'amount'              => ['nullable'],
                'status'              => ['nullable'],
    
            ];
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
