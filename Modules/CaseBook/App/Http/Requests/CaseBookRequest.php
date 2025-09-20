<?php

namespace Modules\CaseBook\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseBookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'amount'       => ['required','integer'],
            'payment_type' => ['required'],
            'payment_date' => ['required'],
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
