<?php

namespace Modules\Page\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $roles =  [
            'product_id'       => ['required'],
            'page_name'        => ['required'],
            'page_heading'     => ['required'],
            'page_link'        => ['required'],
            'product_overview' => ['required'],
            'slider_title'     => ['required'],
            'features'         => ['required'],
            'old_price'        => ['required', 'numeric'],
            'new_price'        => ['nullable', 'numeric', 'lt:old_price'],
            'phone'            => ['required'],
            'status'           => ['required'],
        ];

        if (request()->update_id) {
            $roles['sliderimage']   = ['array'];
        } else {
            $roles['sliderimage']   = ['required', 'array'];
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
