<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CustomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        $violations = [];
        foreach ($errors as $field => $messages) {
            $violation = [];
            foreach ($messages as $message) {
                $violation['message'] = $message;
            }
            $violations[$field] = $violation;
        }

        $response = [
            'status' => 'invalid',
            'message' => 'Request body is not valid.',
            'violations' => $violations,
        ];

        throw new HttpResponseException(response()->json($response, 400));
    }
}
