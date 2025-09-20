<?php

namespace Modules\Customer\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $roles = [
            'name'          => ['required'],
            'phone'         => ['required','unique:customers,phone','min:11'],
            'address'       => ['required'],
            'status'        => ['required'],
        ];

        if (request()->update_id) {
            $roles['phone'] = ['required'];
        }
        
        return $roles;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
