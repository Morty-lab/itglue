<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function updateAll(Request $request)
    {
        try {
            Log::info('Employees UpdateAll Request', [
                'input' => $request->all()
            ]);

            // Validate input
            $validatedData = $request->validate([
                'data' => 'required|array',
                'id' => 'required|uuid|exists:employee_details,id' // Use 'id' instead of 'user_id'
            ]);

            // Find the employee by ID
            $employee = EmployeeInformation::findOrFail($validatedData['id']);

            // Remove any null or empty values
            $updateData = array_filter($validatedData['data'], function($value) {
                return $value !== null && $value !== '';
            });

            // Perform the update
            $employee->update($updateData);

            Log::info('Employees UpdateAll Successful', [
                'employee_id' => $employee->id,
                'updated_fields' => array_keys($updateData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'updated_data' => $updateData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Employees UpdateAll Validation Error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Employees UpdateAll Error', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
