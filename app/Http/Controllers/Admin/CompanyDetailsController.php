<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CompanyDetails;
use App\Models\Credentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyDetailsController extends Controller
{

    public function updateMultipleFields(Request $request)
    {
        try {
            Log::info('WebpageCredential UpdateMultipleFields Request', [
                'input' => $request->all()
            ]);
            // dd($request->all());

            // Validate input
            $validatedData = $request->validate([
                'id' => 'required|exists:credentials,id',
                'data' => 'required|array'
            ]);

            // Find the webpage credential by ID
            $webpageCredential = Credentials::findOrFail($validatedData['id']);

            // Remove any null or empty values
            $updateData = array_filter($validatedData['data'], function ($value) {
                return $value !== null && $value !== '';
            });

            // Perform the update
            $webpageCredential->update($updateData);

            Log::info('WebpageCredential UpdateMultipleFields Successful', [
                'webpage_credential_id' => $webpageCredential->id,
                'updated_fields' => array_keys($updateData)
            ]);
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Credentials Updated successfully',
                'active_tab' => 'webpage-tab'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('WebpageCredential UpdateMultipleFields Validation Error', [
                'errors' => $e->errors()
            ]);

            return redirect()->back()->with([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ]);
        } catch (\Exception $e) {
            Log::error('WebpageCredential UpdateMultipleFields Error', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ]);
        }
    }
}
