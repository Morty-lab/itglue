<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CompanyDetails;
use App\Models\CompanyInformation;
use App\Models\Credentials;
use App\Models\DeviceInformation;
use App\Models\EmployeeInformation;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class OnboardingForm extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $company = CompanyInformation::where('user_id', $user_id)->first();
        $branches = Branch::where('user_id', $user_id)->get();
        $webpage = CompanyDetails::where('user_id', $user_id)->get();

        // Log for debugging
        Log::info('Loading company information', [
            'user_id' => $user_id,
            'company_exists' => $company ? true : false,
            'branch_count' => $branches->count()
        ]);

        return view('onboarding.partials.company-information', [
            'company' => $company,
            'branches' => $branches->toArray()
        ]);
    }

    public function contact_information()
    {
        $user_id = Auth::id();

        // Make sure we're only getting unique employee records
        $employees = EmployeeInformation::where('user_id', $user_id)->get();

        // Retrieve attachments from session, including any flash data
        $employee_attachments = session('employee_attachments') ?? [];

        // Log for debugging
        Log::info('Loading contact information', [
            'user_id' => $user_id,
            'employee_count' => $employees->count(),
            'has_attachments' => !empty($employee_attachments),
            'attachments' => $employee_attachments
        ]);

        // Make sure attachments is always an array
        if (!is_array($employee_attachments)) {
            $employee_attachments = [$employee_attachments];
        }

        return view('onboarding.partials.contact-information', [
            'employees' => $employees,
            'employee_attachments' => $employee_attachments
        ]);
    }

    public function physical_devices()
    {
        $user_id = Auth::id();
        $devices = DeviceInformation::where('user_id', $user_id)->get();

        // Retrieve attachments from session
        $device_attachments = session('device_attachments', []);

        // Ensure it's an array
        if (!is_array($device_attachments)) {
            $device_attachments = [$device_attachments];
        }

        // Filter out empty values
        $device_attachments = array_filter($device_attachments);

        // Log for debugging
        Log::info('Loading physical devices', [
            'user_id' => $user_id,
            'device_count' => $devices->count(),
            'attachment_count' => count($device_attachments)
        ]);

        return view('onboarding.partials.physical-devices', [
            'devices' => $devices,
            'device_attachments' => $device_attachments
        ]);
    }

    public function webpage_development()
    {
        $user_id = Auth::id();
        $webpage_documents = Credentials::where('user_id', $user_id)->get();

        // If no record exists, create an empty object to prevent null reference errors
        if (!$webpage_documents) {
            $webpage_documents = new Credentials();
            $webpage_documents->credential_type = null;
        }

        // Retrieve attachments from session
        $webpage_attachments = session('webpage_attachments', []);

        // Ensure it's an array
        if (!is_array($webpage_attachments)) {
            $webpage_attachments = [$webpage_attachments];
        }

        // Filter out empty values
        $webpage_attachments = array_filter($webpage_attachments);

        // Log for debugging
        Log::info('Loading webpage development', [
            'user_id' => $user_id,
            'has_documents' => !is_null($webpage_documents),
            'attachment_count' => count($webpage_attachments)
        ]);
        // dd($webpage_documents);

        return view('onboarding.partials.webpage-document', [
            'webpages' => $webpage_documents,
            'webpage_attachments' => $webpage_attachments
        ]);
    }

    public function software_licenses()
    {
        $user_id = Auth::id();
        $licenses = License::where('user_id', $user_id)->get();

        // Retrieve attachments from session
        $license_attachments = session('license_attachments', []);

        // Ensure it's an array
        if (!is_array($license_attachments)) {
            $license_attachments = [$license_attachments];
        }

        // Filter out empty values
        $license_attachments = array_filter($license_attachments);

        // Log for debugging
        Log::info('Loading software licenses', [
            'user_id' => $user_id,
            'license_count' => $licenses->count(),
            'attachment_count' => count($license_attachments)
        ]);

        return view('onboarding.partials.software-licensing', [
            'software_licenses' => $licenses,
            'license_attachments' => $license_attachments
        ]);
    }

    public function store_software_license(Request $request)
    {
        $licenses = [];
        $user_id = Auth::id();
        $attachmentPaths = [];


        // Log the input data
        Log::info('Processing software license submission', [
            'count' => count($request->input('software_licenses', [])),
            'has_files' => $request->hasFile('licenses_files')
        ]);

        try {
            // Get existing licenses
            $existingLicenses = License::where('user_id', $user_id)->get();
            $processedLicenseIds = [];

            // Process each license from the form
            foreach ($request->input('software_licenses', []) as $index => $license) {
                // Skip incomplete entries
                if (empty($license['name']) || $license['name'] === 'Please select software licenses used') {
                    continue;
                }

                $licenseName = $license['name'] == 'Other' ? $license['other_name'] : $license['name'];

                $license_data = [
                    "user_id" => $user_id,
                    "software_license" => $licenseName,
                    "quantity" => $license['qty'],
                ];

                // Check if this license already exists (by ID if provided)
                if (!empty($license['id'])) {
                    $existingLicense = License::find($license['id']);

                    if ($existingLicense) {
                        // Update existing license
                        $existingLicense->update($license_data);
                        $licenses[] = $existingLicense->id;
                        $processedLicenseIds[] = $existingLicense->id;
                        continue;
                    }
                }

                // Look for existing license by name
                $existingByName = $existingLicenses->where('software_license', $licenseName)->first();

                if ($existingByName) {
                    // Update existing license found by name
                    $existingByName->update($license_data);
                    $licenses[] = $existingByName->id;
                    $processedLicenseIds[] = $existingByName->id;
                    continue;
                }

                // Create new license
                $newLicense = License::create($license_data);
                if ($newLicense) {
                    $licenses[] = $newLicense->id;
                    $processedLicenseIds[] = $newLicense->id;
                } else {
                    throw new \Exception('Failed to add Software License!');
                }
            }

            // Handle File Uploads
            if ($request->hasFile('licenses_files')) {
                // Get existing attachments from session (if any)
                $existingAttachments = session('license_attachments', []);

                // Make sure it's an array
                if (!is_array($existingAttachments)) {
                    $existingAttachments = [$existingAttachments];
                }

                // Filter out empty values
                $existingAttachments = array_filter($existingAttachments);

                foreach ($request->file('licenses_files') as $file) {
                    // Validate file
                    $this->validateUploadedFile($file);

                    $filename = now()->format('Ymd') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('licenses_files', $filename, 'public');
                    $attachmentPaths[] = $path;
                }

                // Combine existing and new attachments
                $allAttachments = array_merge($existingAttachments, $attachmentPaths);

                // Store attachments in session as an array
                if (!empty($allAttachments)) {
                    session()->put('license_attachments', $allAttachments);

                    // Log what we're storing in session
                    Log::info('Storing license attachments in session', [
                        'existing_count' => count($existingAttachments),
                        'new_count' => count($attachmentPaths),
                        'total_count' => count($allAttachments)
                    ]);
                }
            }

            // Log successful processing
            Log::info('Software license processing complete', [
                'processed_count' => count($licenses),
                'license_ids' => implode(',', $licenses),
                'attachment_count' => count($attachmentPaths)
            ]);

        } catch (\Throwable $th) {
            Log::error('Error processing software licenses', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            return redirect()->back()->withErrors($th->getMessage())->withInput();
        }

        // Redirect to the completed page or dashboard
        return redirect()->route('onboarding')->with('success', 'Software licenses saved successfully!');
    }

    /**
     * Helper method to validate uploaded files
     * Add this method if you don't already have it in your controller
     */
    private function validateUploadedFile($file)
    {
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        $allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.jgraph.mxfile', // draw.io file type
            'application/vnd.jgraph.mxfile.realtime',
            'application/vnd.jgraph.mxfile.cached'
        ];

        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'drawio') {
            return true; // Allow draw.io files based on extension
        }
        // Check file size
        if ($file->getSize() > $maxFileSize) {
            throw new \Exception("File {$file->getClientOriginalName()} exceeds 2MB limit");
        }


        // Check file type
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \Exception("File {$file->getClientOriginalName()} is not an allowed file type");
        }
    }

    public function store_employees(Request $request)
    {
        $employees = [];
        $attachmentPaths = [];

        try {
            // Log incoming data for debugging
            Log::info('Processing employee submissions', [
                'count' => count($request->input('employees')),
                'has_files' => $request->hasFile('employee_files')
            ]);

            // Process Employees
            foreach ($request->input('employees') as $index => $employee) {
                $employee_data = [
                    "user_id" => Auth::id(),
                    "firstname" => $employee['first_name'],
                    "lastname" => $employee['last_name'],
                    "employee_email" => $employee['email'],
                    "employee_title" => $employee['title'],
                    "employee_working_location" => $employee['working_location'],
                    "employee_phone_number" => $employee['mobile_number'],
                    "employee_working_number" => $employee['work_phone'],
                    "notes" => $employee['notes'],
                ];

                // Try to find existing employee
                $existing_employee = EmployeeInformation::where('user_id', Auth::id())
                    ->where('employee_email', $employee['email'])
                    ->first();

                if ($existing_employee) {
                    // Update existing employee
                    Log::info('Updating existing employee', [
                        'id' => $existing_employee->id,
                        'email' => $employee['email']
                    ]);

                    $existing_employee->update($employee_data);
                    $employees[] = $existing_employee->id;
                } else {
                    // Create new employee
                    Log::info('Creating new employee', [
                        'email' => $employee['email']
                    ]);

                    $employee_record = EmployeeInformation::add($employee_data);
                    $employees[] = $employee_record->id;
                }
            }

            // Handle File Uploads
            if ($request->hasFile('employee_files')) {
                // Get existing attachments from session (if any)
                $existingAttachments = session('employee_attachments', []);

                // Make sure it's an array
                if (!is_array($existingAttachments)) {
                    $existingAttachments = [$existingAttachments];
                }

                // Filter out empty values
                $existingAttachments = array_filter($existingAttachments);

                foreach ($request->file('employee_files') as $file) {
                    // Validate file
                    $this->validateUploadedFile($file);

                    $filename = now()->format('Ymd') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('employee_files', $filename, 'public');
                    $attachmentPaths[] = $path;
                }

                // Combine existing and new attachments
                $allAttachments = array_merge($existingAttachments, $attachmentPaths);

                // Store attachments in session as an array (use put instead of flash)
                if (!empty($allAttachments)) {
                    session()->put('employee_attachments', $allAttachments);

                    // Log what we're storing in session
                    Log::info('Storing attachments in session', [
                        'existing_count' => count($existingAttachments),
                        'new_count' => count($attachmentPaths),
                        'total_count' => count($allAttachments),
                        'paths' => $allAttachments
                    ]);
                }
            }

            // Log successful processing
            Log::info('Employee processing complete', [
                'processed_employees' => count($employees),
                'employee_ids' => implode(',', $employees),
                'attachment_count' => count($attachmentPaths)
            ]);

        } catch (\Throwable $th) {
            Log::error('Error in employee submission', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            return redirect()->back()->withErrors('Failed to process employee information: ' . $th->getMessage());
        }

        return redirect()->route('onboarding.physical_devices');
    }

    // Helper method to validate uploaded files

    public function store_devices(Request $request)
    {
        $devices = [];
        $attachmentPaths = [];

        try {
            // Log incoming data for debugging
            Log::info('Processing device submissions', [
                'count' => count($request->input('devices')),
                'has_files' => $request->hasFile('device_files')
            ]);

            foreach ($request->input('devices') as $index => $device) {
                $device_data = [
                    "user_id" => Auth::id(),
                    "device_type" => $device['type'],
                    "other_device_type" => $device['other_type'],
                    "device_name" => $device['name'],
                    "device_username" => $device['username'],
                    "primary_password" => Hash::make($device['primary_password']),
                    "device_ip_address" => $device['ip_address'],
                    "device_location" => $device['location'],
                    "additional_passwords" => $device['additional_passwords'],
                    "notes" => $device['notes'],
                ];

                // Check if an ID was provided (existing device)
                if (!empty($device['id'])) {
                    $existing_device = DeviceInformation::find($device['id']);

                    if ($existing_device) {
                        // Update existing device
                        Log::info('Updating existing device', [
                            'id' => $existing_device->id,
                            'name' => $device['name']
                        ]);

                        $existing_device->update($device_data);
                        $devices[] = $existing_device->id;
                        continue;
                    }
                }

                // Check if device with same name exists
                $existing_by_name = DeviceInformation::where('user_id', Auth::id())
                    ->where('device_name', $device['name'])
                    ->first();

                if ($existing_by_name) {
                    // Update existing device
                    Log::info('Found existing device by name', [
                        'id' => $existing_by_name->id,
                        'name' => $device['name']
                    ]);

                    $existing_by_name->update($device_data);
                    $devices[] = $existing_by_name->id;
                    continue;
                }

                // Create new device
                Log::info('Creating new device', [
                    'name' => $device['name']
                ]);

                $device_record = DeviceInformation::add($device_data);
                if ($device_record) {
                    $devices[] = $device_record->id;
                } else {
                    throw new \Exception('Failed to add Device Information!');
                }
            }

            // Handle File Uploads
            if ($request->hasFile('device_files')) {
                // Get existing attachments from session (if any)
                $existingAttachments = session('device_attachments', []);

                // Make sure it's an array
                if (!is_array($existingAttachments)) {
                    $existingAttachments = [$existingAttachments];
                }

                // Filter out empty values
                $existingAttachments = array_filter($existingAttachments);

                foreach ($request->file('device_files') as $file) {
                    $filename = now()->format('Ymd') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('device_files', $filename, 'public');
                    $attachmentPaths[] = $path;
                }

                // Combine existing and new attachments
                $allAttachments = array_merge($existingAttachments, $attachmentPaths);

                // Store attachments in session as an array
                if (!empty($allAttachments)) {
                    session()->put('device_attachments', $allAttachments);

                    // Log what we're storing in session
                    Log::info('Storing device attachments in session', [
                        'existing_count' => count($existingAttachments),
                        'new_count' => count($attachmentPaths),
                        'total_count' => count($allAttachments)
                    ]);
                }
            }

            // Log successful processing
            Log::info('Device processing complete', [
                'processed_devices' => count($devices),
                'attachment_count' => count($attachmentPaths)
            ]);

        } catch (\Throwable $th) {
            Log::error('Error adding device information', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            return redirect()->back()->withErrors($th->getMessage());
        }

        return redirect()->route('onboarding.webpage_development');
    }

    public function store_webpage_development(Request $request)
    {
        $attachmentPaths = [];

        // dd($request->all());

        try {
            $user_id = Auth::id();

            // Prepare the data to be saved
            foreach ($request->input('webpage_document') as $webpage) {
                $credentials = Credentials::where('id', $webpage['id'])->first();

                $webpage_data = array_merge(
                    $webpage,
                    ["user_id" => $user_id]
                );

                // dd($webpage_data);

                if ($credentials) {
                    // Update existing record
                    $credentials->update($webpage_data);
                } else {
                    // Create new record
                    $credentials = Credentials::create($webpage_data);
                }

                Log::info('Webpage development processing complete', [
                    'webpage_id' => $credentials->id,
                    'attachment_count' => count($attachmentPaths)
                ]);
            }


            // Handle File Uploads
            if ($request->hasFile('webpage_files')) {
                // Get existing attachments from session (if any)
                $existingAttachments = session('webpage_attachments', []);

                // Make sure it's an array
                if (!is_array($existingAttachments)) {
                    $existingAttachments = [$existingAttachments];
                }

                // Filter out empty values
                $existingAttachments = array_filter($existingAttachments);

                foreach ($request->file('webpage_files') as $file) {
                    $filename = now()->format('Ymd') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('webpage_files', $filename, 'public');
                    $attachmentPaths[] = $path;
                }

                // Combine existing and new attachments
                $allAttachments = array_merge($existingAttachments, $attachmentPaths);

                // Store attachments in session as an array
                if (!empty($allAttachments)) {
                    session()->put('webpage_attachments', $allAttachments);

                    // Log what we're storing in session
                    Log::info('Storing webpage attachments in session', [
                        'existing_count' => count($existingAttachments),
                        'new_count' => count($attachmentPaths),
                        'total_count' => count($allAttachments)
                    ]);
                }
            }


        } catch (\Throwable $th) {
            Log::error('Error adding webpage development', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            return redirect()->back()->withErrors('Failed to add Company Details: ' . $th->getMessage());
        }

        return redirect()->route('onboarding.software_licenses');
    }

    public function create()
    {
        // Method left empty
    }

    public function store_company_details(Request $request)
    {
        // Log all form data for debugging
        Log::info('Company details submission', $request->except(['_token']));

        $branches = [];
        $company_id = "";

        // Prepare company data
        $company_data = [
            "user_id" => Auth::id(),
            "company_name" => $request->input('company_name'),
            "primary_number" => $request->input('primary_number'),
            "secondary_number" => $request->input('secondary_number'),
            "hq_location_name" => $request->input('hq_location_name'),
            "hq_address" => $request->input('hq_address'),
            "hq_city" => $request->input('hq_city'),
            "hq_state" => $request->input('hq_state'),
            "hq_postal_code" => $request->input('hq_postal_code'),
            "hq_country" => $request->input('hq_country'),
            "hq_province" => $request->input('hq_province'),
            "hq_fax" => $request->input('hq_fax'),
            "hq_website" => $request->input('hq_website'),
            "hq_opening_time" => $request->input('hq_opening_time'),
            "hq_closing_time" => $request->input('hq_closing_time'),
        ];

        try {
            // Check if company already exists
            $existing_company = CompanyInformation::where('user_id', Auth::id())->first();

            if ($existing_company) {
                // Update existing company
                CompanyInformation::where('id', $existing_company->id)->update($company_data);
                $company_id = $existing_company->id;
                Log::info('Updated existing company', ['id' => $company_id]);
            } else {
                // Create new company
                $company = CompanyInformation::add($company_data);
                $company_id = $company->id;
                Log::info('Created new company', ['id' => $company_id]);
            }
        } catch (\Throwable $th) {
            Log::error('Failed to add Company Information', ['error' => $th->getMessage()]);
            return redirect()->back()->withErrors('Failed to add Company Information: ' . $th->getMessage());
        }

        // Process branches if they exist in the request
        if ($request->has('branches')) {
            try {
                Log::info('Processing branches', ['count' => count($request->input('branches'))]);

                // First, get all existing branches for this user
                $existingBranches = Branch::where('user_id', Auth::id())->get();
                $existingBranchIds = $existingBranches->pluck('id')->toArray();
                $processedBranchIds = [];

                foreach ($request->input('branches') as $index => $branch) {
                    Log::info('Processing branch', ['index' => $index, 'data' => $branch]);

                    $branch_data = [
                        "user_id" => Auth::id(),
                        "company_id" => $company_id,
                        "branch_address" => $branch['address'],
                        "phone_number" => $branch['phone_number'] ?? null,
                        "fax" => $branch['fax'] ?? null,
                        "website" => $branch['website'] ?? null,
                        "opening_time" => $branch['opening_time'] ?? null,
                        "closing_time" => $branch['closing_time'] ?? null,
                    ];

                    // Check for an ID in the form
                    if (!empty($branch['id'])) {
                        $branch_record = Branch::find($branch['id']);

                        if ($branch_record) {
                            // Update existing branch
                            $branch_record->update($branch_data);
                            $branches[] = $branch_record->id;
                            $processedBranchIds[] = $branch_record->id;
                            Log::info('Updated existing branch', ['id' => $branch_record->id]);
                        } else {
                            // ID provided but branch not found - create new
                            $new_branch = Branch::add($branch_data);
                            $branches[] = $new_branch->id;
                            $processedBranchIds[] = $new_branch->id;
                            Log::info('Created new branch (ID not found)', ['id' => $new_branch->id]);
                        }
                    } else {
                        // No ID provided - create new branch
                        $new_branch = Branch::add($branch_data);
                        $branches[] = $new_branch->id;
                        $processedBranchIds[] = $new_branch->id;
                        Log::info('Created new branch', ['id' => $new_branch->id]);
                    }
                }

                $branch_ids = implode(',', $branches);
                Log::info('Processed all branches', ['ids' => $branch_ids]);
            } catch (Throwable $th) {
                Log::error('Failed to add Branch Information', ['error' => $th->getMessage(), 'trace' => $th->getTraceAsString()]);
                return redirect()->back()->withErrors('Failed to add Branch Information: ' . $th->getMessage());
            }
        } else {
            Log::info('No branches submitted in the request');
        }

        // Handle file uploads if any
        if ($request->hasFile('company_attachments')) {
            try {
                $attachmentPaths = [];
                foreach ($request->file('company_attachments') as $file) {
                    $filename = now()->format('Ymd') . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('company_attachments', $filename, 'public');
                    $attachmentPaths[] = $path;
                }

                // Update company with attachment paths
                if (!empty($attachmentPaths)) {
                    CompanyInformation::where('id', $company_id)
                        ->update(['attachment' => implode(',', $attachmentPaths)]);
                }
            } catch (Throwable $th) {
                Log::error('Failed to upload attachments', ['error' => $th->getMessage()]);
                // Continue even if attachments fail
            }
        }

        return redirect()->route('onboarding.contact_information')
            ->with('company_id', $company_id)
            ->with('branch_ids', !empty($branches) ? implode(',', $branches) : '');
    }

    public function store(Request $request)
    {
        $attachments = [
            'webpage_files' => $request->hasFile('webpage_files'),
            'licenses_files' => $request->hasFile('licenses_files'),
            'device_files' => $request->hasFile('device_files'),
            'company_attachments' => $request->hasFile('company_attachments'),
            'employee_files' => $request->hasFile('employee_files')
        ];

        $attachment_paths = [];
        foreach ($attachments as $key => $value) {
            if ($value) {
                $files = $request->file($key);
                foreach ($files as $file) {
                    // Generate custom filename: YYYYMMDD_random3digits.extension
                    $filename = now()->format('Ymd') . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();

                    // Store the file in Laravel storage
                    $path = $file->storeAs($key, $filename, 'public');
                    $attachment_paths[] = $path;
                }
            }
        }

        $final_paths = implode(',', $attachment_paths);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function destroy_credentials(string $id)
    {
        try {
            $credentials = Credentials::findOrFail($id);
            $credentials->delete();

            Log::info('Credentials deleted successfully', ['id' => $id]);

            return redirect()->back()->with('success', 'Credentials deleted successfully!');
        } catch (\Throwable $th) {
            Log::error('Failed to delete Credentials', ['error' => $th->getMessage()]);
            return redirect()->back()->withErrors('Failed to delete Credentials: ' . $th->getMessage());
        }
    }


    public function show(string $id)
    {
        // Method left empty
    }

    public function edit(string $id)
    {
        // Method left empty
    }

    public function update(Request $request, string $id)
    {
        // Method left empty
    }

    public function destroy(string $id)
    {
        // Method left empty
    }
}
