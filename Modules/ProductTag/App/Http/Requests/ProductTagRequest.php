<?php

namespace Modules\ProductTag\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductTagRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $roles = [
            'name' => ['required',Rule::unique('product_tags', 'tag_name')->ignore(request()->update_id) ],
            'status' => ['required'],
        ];

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
