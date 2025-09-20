<?php

namespace Modules\Slider\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'status'             => ['required'],
            'order_by'           => ['required'],
        ];
        if(config('settings.sliderchosevalue') == 1){
            if (request()->has('update_id')) {
                // ,'dimensions:width=1320,height=250'
                // 'dimensions:width=320,height=150'
                $rules['slider_image']   = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
            } else {
                $rules['slider_image']   = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
            }
        }else if(config('settings.sliderchosevalue') == 2){
            if (request()->has('update_id')) {
                // ,'dimensions:width=990,height=500'
                // ,'dimensions:width=420,height=250'
                $rules['slider_image']   = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
            } else {
                $rules['slider_image']   = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
            }
        }else if(config('settings.sliderchosevalue') == 3){
            if (request()->has('update_id')) {
                // ,'dimensions:width=990,height=400'
                // , 'dimensions:width=420,height=200'
                $rules['slider_image']   = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
            } else {
                $rules['slider_image']   = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
            }
        }else if(config('settings.sliderchosevalue') == 4){
            if (request()->has('update_id')) {
                // ,'dimensions:width=990,height=500'
                // ,'dimensions:width=420,height=250'
                $rules['slider_image']   = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
            } else {
                $rules['slider_image']   = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
            }
        }else{
            if (request()->has('update_id')) {
                // ,'dimensions:width=1320,height=500'
                // ,'dimensions:width=320,height=250'
                $rules['slider_image']   = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'nullable','image', 'mimes:jpeg,png,jpg,gif','max:2048'];
            } else {
                $rules['slider_image']   = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
                $rules['slider_m_image'] = [ 'required','image','mimes:jpeg,png,jpg,gif','max:2048'];
            }
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
