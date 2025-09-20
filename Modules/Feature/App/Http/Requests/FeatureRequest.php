<?php

namespace Modules\Feature\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeatureRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $roles = [
            'title'       => ['required', 'max:255'], 
            'icon'        => ['required'],
            'description' => ['required'],
            'status'      => ['required', 'in:0,1'], 
        ];
        
        if (request()->update_id) {
            
            $roles['title']       = ['required', 'max:255'];
            $roles['icon']        = ['required'];
            $roles['description'] = ['required'];
            $roles['status']      = ['required', 'in:0,1'];
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
