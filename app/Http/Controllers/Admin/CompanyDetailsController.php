<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyDetailsController extends Controller
{
    /**
     * Update the specified company details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMultipleFields(Request $request)
    {
        try {
            Log::info('WebpageCredential UpdateMultipleFields Request', [
                'input' => $request->all()
            ]);

            // Validate input
            $validatedData = $request->validate([
                'id' => 'required|exists:company_details,id',
                'data' => 'required|array'
            ]);

            // Find the webpage credential by ID
            $webpageCredential = CompanyDetails::findOrFail($validatedData['id']);

            // Remove any null or empty values
            $updateData = array_filter($validatedData['data'], function($value) {
                return $value !== null && $value !== '';
            });

            // Perform the update
            $webpageCredential->update($updateData);

            Log::info('WebpageCredential UpdateMultipleFields Successful', [
                'webpage_credential_id' => $webpageCredential->id,
                'updated_fields' => array_keys($updateData)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'updated_data' => $updateData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('WebpageCredential UpdateMultipleFields Validation Error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('WebpageCredential UpdateMultipleFields Error', [
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
