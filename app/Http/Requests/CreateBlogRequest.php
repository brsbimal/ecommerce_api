<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class CreateBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'title' => 'required|string|min:3|max:64',
        ];
        if($this->has('cover_image')){
            $rules = $rules+[
                            'cover_image' => 'required|mimes:jpg,png,jpeg,gif|max:5000',
                        ];
        }
        if($this->has('type')){
            $rules += [ 
                        'type' => 'required|string|min:3|max:32'
                    ];
        }
        if($this->has('desc')){
            $rules += [
                'desc' => 'required|min:3'
            ];
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'title.required' => 'Category Name is required.',
            'title.min' => 'Category Name not less than 3 character.',
            'title.max' => 'Category Name not greater than 64 character.',

            'desc.required' => 'Descriptions is required.',
            'desc.min' => 'Descriptions is more than 3 character',

            'type.required' => 'Type is required.',
            'type.string' => 'Type must be a string.',
            'type.min' => 'Type not less than 3 character.',
            'type.max' => 'Type not greater than 64 character.',

            'cover_image.mimes' => 'Please select valid image format',
            'cover_image.max' => 'Image is not greater than 5 MB',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
