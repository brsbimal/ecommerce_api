<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|min:3|max:64',
            'category_id' => 'required|integer|exists:mysql.app_categories,id',
            'short_desc' => 'required|min:3|max:255',
            'desc' => 'required|min:3',
            'is_active' => 'required|boolean',
            'is_new' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'price' => 'required|numeric',
            'vat' => 'required|numeric'
        ];
        if($this->has('img1')){
            $rules = $rules+[
                            'img1' => 'required|mimes:jpg,png,jpeg,gif|max:5000',
                        ];
        }
        if($this->has('img2')){
            $rules = $rules+[
                            'img2' => 'required|mimes:jpg,png,jpeg,gif|max:5000',
                        ];
        }
        if($this->has('img3')){
            $rules = $rules+[
                            'img3' => 'required|mimes:jpg,png,jpeg,gif|max:5000',
                        ];
        }
        if($this->has('img4')){
            $rules = $rules+[
                            'img4' => 'required|mimes:jpg,png,jpeg,gif|max:5000',
                        ];
        }

        return $rules;
    }
    public function messages()
    {
        return [

            'name.required' => 'Product Name is required.',
            'name.min' => 'Product Name not less than 3 character.',
            'name.max' => 'Product Name not greater than 64 character.',
            'short_desc.min' => 'Short desc is more than 3 character',
            'short_desc.max' => 'Short desc is less than 255 character',
            'desc.required' => 'Descriptions is required.',

            'img1.required' => 'Please choose product Image',
            'img1.mimes' => 'Please enter valid image format',
            'img1.max' => 'Image size not greater than 500 KB',

            'img2.required' => 'Please choose product image',
            'img2.mimes' => 'Please enter valid image format',
            'img2.max' => 'Image size not greater than 500 KB',

            'img3.required' => 'Please choose product image',
            'img3.mimes' => 'Please enter valid image format',
            'img3.max' => 'Image size not greater than 500 KB',

            'img4.required' => 'Please choose product image',
            'img4.mimes' => 'Please enter valid image format',
            'img4.max' => 'Image size not greater than 500 KB',

            'price.numeric' => 'Please enter numeric value',
            'vat.numeric' => 'please enter numeric value'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
