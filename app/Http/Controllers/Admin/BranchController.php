<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    public function update(Request $request, Branch $branch)
    {
        try {
            Log::info('Branch Update Request', [
                'branch_id' => $branch->id,
                'input' => $request->all()
            ]);

            // Validate input
            $validatedData = $request->validate([
                'branch_address' => 'nullable|string|max:255',
                'phone_number' => 'nullable|string|max:255',
                'fax' => 'nullable|string|max:255',
                'website' => 'nullable|string|max:255',
                'opening_time' => 'nullable|string|max:255',
                'closing_time' => 'nullable|string|max:255'
            ]);

            // Remove any null or empty values
            $updateData = array_filter($validatedData, function($value) {
                return $value !== null && $value !== '';
            });

            // Perform the update
            $branch->update($updateData);

            Log::info('Branch Update Successful', [
                'branch_id' => $branch->id,
                'updated_fields' => array_keys($updateData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'updated_data' => $updateData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Branch Update Validation Error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Branch Update Error', [
                'branch_id' => $branch->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAll(Request $request)
    {
        try {
            Log::info('Branches UpdateAll Request', [
                'input' => $request->all()
            ]);

            // Validate input
            $validatedData = $request->validate([
                'data' => 'required|array',
                'id' => 'required|uuid|exists:branch_information,id' // Use 'id' instead of 'user_id'
            ]);

            // Find the branch by ID
            $branch = Branch::findOrFail($validatedData['id']);

            // Remove any null or empty values
            $updateData = array_filter($validatedData['data'], function($value) {
                return $value !== null && $value !== '';
            });

            // Perform the update
            $branch->update($updateData);

            Log::info('Branches UpdateAll Successful', [
                'branch_id' => $branch->id,
                'updated_fields' => array_keys($updateData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'updated_data' => $updateData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Branches UpdateAll Validation Error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Branches UpdateAll Error', [
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
