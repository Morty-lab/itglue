<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyInformationController extends Controller
{
    /**
     * Update the specified company information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Find the company information by ID
        $companyInformation = CompanyInformation::findOrFail($id);

        // Update the company information
        $companyInformation->name = $request->input('name');
        $companyInformation->address = $request->input('address');
        $companyInformation->email = $request->input('email');
        $companyInformation->phone = $request->input('phone');
        $companyInformation->save();

        // Return a response
        return response()->json([
            'message' => 'Company information updated successfully',
            'data' => $companyInformation
        ], 200);
    }

    /**
     * Update multiple fields of the specified company information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateMultipleFields(Request $request)
    {
        try {
            Log::info('CompanyInformation UpdateMultipleFields Request', [
                'input' => $request->all()
            ]);

            // Validate input
            $validatedData = $request->validate([
                'companies' => 'required|array',
                'companies.*.user_id' => 'required|exists:company_information,user_id',
                'companies.*.fields' => 'required|array'
            ]);

            foreach ($validatedData['companies'] as $companyData) {
                // Find the company information by user_id
                $companyInformation = CompanyInformation::where('user_id', $companyData['user_id'])->firstOrFail();

                // Remove any null or empty values
                $updateData = array_filter($companyData['fields'], function($value) {
                    return $value !== null && $value !== '';
                });

                // Perform the update
                $companyInformation->update($updateData);

                Log::info('CompanyInformation UpdateMultipleFields Successful', [
                    'company_information_id' => $companyInformation->user_id,
                    'updated_fields' => array_keys($updateData)
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'updated_data' => $validatedData['companies']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('CompanyInformation UpdateMultipleFields Validation Error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('CompanyInformation UpdateMultipleFields Error', [
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
