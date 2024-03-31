<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ValidatesRequest
{
    protected function validateRequest(Request $request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        return $validator->validate();
    }

    protected function handleValidationFailure($validator)
    {
        $errors = $validator->errors();
        $violations = [];

        foreach ($errors->all() as $field => $message) {
            $violations[$field] = ['message' => $message];
        }

        return response()->json([
            'status' => 'invalid',
            'message' => 'Request body is not valid.',
            'violations' => $violations,
        ], 400);
    }
}
