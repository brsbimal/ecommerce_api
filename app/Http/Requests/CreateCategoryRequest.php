<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CreateCategoryRequest extends FormRequest
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
            'desc' => 'required',
        ];
        if($this->has('logo')){
            $rules = $rules+[
                            'logo' => 'required|mimes:jpg,png,jpeg,gif|max:5000',
                        ];
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'name.required' => 'Category Name is required.',
            'name.min' => 'Category Name not less than 3 character.',
            'name.max' => 'Category Name not greater than 64 character.',
            'desc.required' => 'Descriptions is required.',
            'logo.required' => 'Please choose logo',
            'logo.mimes' => 'Please enter valid image format',
            'logo.max' => 'Image size not greater than 500 KB'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
