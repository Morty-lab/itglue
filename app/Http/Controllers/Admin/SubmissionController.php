<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInformation;
use App\Models\EmployeeInformation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\CompanyDetails;
use App\Models\DeviceInformation;
use App\Models\License;
namespace App\Http\Controllers\Admin;
use App\Models\CompanyInformation;
use App\Models\User;
use App\Models\EmployeeInformation;
use App\Models\Branch;
use App\Models\CompanyDetails;
use App\Models\DeviceInformation;
use App\Models\License;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth;

class SubmissionController extends Controller
{

    public function updateMultipleCompanyFields(Request $request)
    {
        // Ensure method is PUT or PATCH
        if (!$request->isMethod('put') && !$request->isMethod('patch')) {
            Log::error('Invalid request method', [
                'method' => $request->method(),
                'path' => $request->path()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        try {
            // Validate input
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'fields' => 'required|array'
            ]);

            // Log incoming data for debugging
            Log::info('Received update request', [
                'user_id' => $validatedData['user_id'],
                'fields' => $validatedData['fields']
            ]);

            // Find the company
            $company = CompanyInformation::where('user_id', $validatedData['user_id'])->first();

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            // Update fields
            foreach ($validatedData['fields'] as $field => $value) {
                $company->$field = $value;
            }

            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'Fields updated successfully',
                'updated_fields' => array_keys($validatedData['fields'])
            ]);

        } catch (\Exception $e) {
            // Log full error details
            Log::error('Update error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return a JSON response with error details
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCompany(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json(['message' => 'Company updated successfully']);
    }

    public function index()
    {
        // List all submissions
        $submissions = CompanyInformation::all();

        // Format the data for the view
        $formattedSubmissions = [];
        foreach ($submissions as $submission) {
            $user = User::find($submission->user_id);
            $employees = EmployeeInformation::where('user_id', $submission->user_id)->count();
            $devices = DeviceInformation::where('user_id', $submission->user_id)->count();
            $licenses = License::where('user_id', $submission->user_id)->count();
            $branches = Branch::where('company_id', $submission->id)->count();

            $formattedSubmissions[] = [
                'id' => $submission->id,
                'company' => $submission,
                'user' => $user,
                'counts' => [
                    'employees' => $employees,
                    'devices' => $devices,
                    'licenses' => $licenses,
                    'branches' => $branches
                ],
                'status' => $submission->approval_status ?? 'pending',
                'submitted_at' => $submission->created_at,
            ];
        }

        return view('admin.submissions', ['submissions' => $formattedSubmissions]);
    }

    public function show($id)
    {
        // Get details for a specific submission
        $company = CompanyInformation::findOrFail($id);
        $user = User::findOrFail($company->user_id);
        $branches = Branch::where('company_id', $company->id)->get();
        $employees = EmployeeInformation::where('user_id', $company->user_id)->get();
        $devices = DeviceInformation::where('user_id', $company->user_id)->get();
        $licenses = License::where('user_id', $company->user_id)->get();
        $webpage = CompanyDetails::where('user_id', $company->user_id)->first();

        return view('admin.submission-details', [
            'company' => $company,
            'user' => $user,
            'branches' => $branches,
            'employees' => $employees,
            'devices' => $devices,
            'licenses' => $licenses,
            'webpage' => $webpage,
            'employee_attachments' => [],
            'device_attachments' => [],
            'license_attachments' => [],
            'webpage_attachments' => [],
        ]);
    }

    public function update(Request $request, $id)
    {
        // Define allowed fields
        $allowedFields = [
            'branch_address',
            'phone_number',
            'fax',
            'website',
            'opening_time',
            'closing_time'
        ];

        // Validate input
        $validatedData = $request->validate(
            collect($allowedFields)->mapWithKeys(function ($field) {
                return [$field => 'nullable|string|max:255'];
            })->toArray()
        );

        try {
            // Update only the fields that are present in the request
            $branch = Branch::findOrFail($id);
            $branch->update(
                collect($validatedData)
                    ->filter(function ($value) {
                        return $value !== null;
                    })
                    ->toArray()
            );

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed'
            ], 500);
        }
    }
}
