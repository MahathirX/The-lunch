<?php

namespace Modules\Brand\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $roles = [
            'name' => [
                'required',
                Rule::unique('brands', 'name')->ignore(request()->update_id)
            ],
            'status' => ['required'],
        ];

        if (request()->update_id) {
            $roles['image'] = ['image', 'mimes:jpeg,png,jpg,gif'];
        } else {
            $roles['image'] = ['image', 'mimes:jpeg,png,jpg,gif'];
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
