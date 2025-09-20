<?php

namespace Modules\Product\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name'               => ['required', 'string', 'max:255'],
            'productcategory_id' => ['required', 'array', 'min:1'],
            'productcategory_id.*' => ['exists:categories,id'],
            'price'              => ['required', 'numeric', 'min:0'],
            'discount_price'     => ['nullable', 'numeric', 'lt:price'],
            'product_image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
            'status'             => ['required'],
            'short_description'  => ['required', 'string'],
            'description'        => ['required', 'string'],
            'product_location'   => ['required', 'string'],
            'available_date'     => ['required', 'date'],
            'package_type'       => ['required', 'string'],
        ];

        // Items validation
        $rules['items'] = ['required', 'array', 'min:1'];
        $rules['items.*.item_name'] = ['required', 'string'];
        $rules['items.*.qty'] = ['required', 'integer', 'min:1'];
        $rules['items.*.price'] = ['required', 'numeric', 'min:0'];

        // Color & Size attributes (only if producttype == 1)
        if ($this->producttype == '1') {
            $rules['colorattributes'] = ['required', 'array', 'min:1'];
            $rules['sizeattributes'] = ['required', 'array', 'min:1'];
            $rules['colorattributes.*'] = ['required', 'exists:colors,id'];
            $rules['sizeattributes.*'] = ['required', 'exists:sizes,id'];
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}
