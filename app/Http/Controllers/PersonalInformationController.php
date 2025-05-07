<?php

namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonalInformationController extends Controller
{
    public function createIdentity(Request $request): JsonResponse
    {
        try {
            $validatorData = Validator::make($request->all(), [
                'matrix_id' => 'required|string|unique:personal_information,matrix_id',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'birth_date' => 'required|date|before:today',
                'gender' => 'required|string|in:woman,man',
                'address' => 'required|string',
                'phone_number' => 'required|string',
                'departement' => 'required|string',
                'study_program' => 'required|string',
                'entry_year' => 'required|integer'
            ]);
            if ($validatorData->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'error' => $validatorData->errors()
                ], status: 422);
            }
            $validatedData = $validatorData->validate();
            $fullName = $validatedData['first_name'] . ' ' . $validatedData['last_name'];
            $validatedData['full_name'] = $fullName;
            $data = PersonalInformation::create($validatedData);
            return response()->json([
                'message' => 'Successfully generate an identity information',
                'data' => $data,
            ], status: 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'error' => $th->getMessage()
            ], status: 500);
        }
    }
    public function editIdentity(Request $request, $id): JsonResponse
    {
        try {
            $validatorData = Validator::make($request->all(), [
                'matrix_id' => 'required|string|unique:personal_information,matrix_id,' . $id,
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'birth_date' => 'required|date|before:today',
                'gender' => 'required|string|in:woman,man',
                'address' => 'required|string',
                'phone_number' => 'required|string',
                'departement' => 'required|string',
                'study_program' => 'required|string',
                'entry_year' => 'required|integer'
            ]);

            if ($validatorData->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'error' => $validatorData->errors()
                ], 422);
            }

            $validatedData = $validatorData->validate();

            $validatedData['full_name'] = $validatedData['first_name'] . ' ' . $validatedData['last_name'];

            $personalInformation = PersonalInformation::findOrFail($id);

            $changes = [];
            foreach ($validatedData as $key => $value) {
                if ($personalInformation->$key !== $value) {
                    $changes[$key] = $value;
                }
            }

            if (!empty($changes)) {
                $personalInformation->update($changes);
            }


            return response()->json([
                'message' => 'Successfully updated the identity information',
                'data' => $personalInformation
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Record not found',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'error' => $th->getMessage()
            ], 500);
        }
    }

}
