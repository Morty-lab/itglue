<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CompanyInformation;
use App\Models\EmployeeInformation;
use App\Models\DeviceInformation;
use App\Models\CompanyDetails;
use App\Models\License;
use App\Models\Branch;
use App\Services\ITGlueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * The IT Glue service instance.
     *
     * @var \App\Services\ITGlueService
     */
    protected $itglueService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\ITGlueService  $itglueService
     * @return void
     */
    public function __construct(ITGlueService $itglueService)
    {
        $this->itglueService = $itglueService;
    }

    /**
     * Display the admin dashboard with all user submissions
     */
    public function index()
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        // Get all users except admin
        $users = User::where('role', '!=', 'admin')->get();

        // Get submission data for each user
        $submissions = [];
        foreach ($users as $user) {
            $company = CompanyInformation::where('user_id', $user->id)->first();
            $employees = EmployeeInformation::where('user_id', $user->id)->count();
            $devices = DeviceInformation::where('user_id', $user->id)->count();
            $licenses = License::where('user_id', $user->id)->count();
            $branches = Branch::where('user_id', $user->id)->count();
            $webpage = CompanyDetails::where('user_id', $user->id)->first();

            $submissions[] = [
                'user' => $user,
                'company' => $company,
                'counts' => [
                    'employees' => $employees,
                    'devices' => $devices,
                    'licenses' => $licenses,
                    'branches' => $branches
                ],
                'webpage' => $webpage,
                'status' => $company ? $company->approval_status : 'pending',
                'submitted_at' => $company ? $company->created_at : null,
            ];
        }

        return view('admin.dashboard', [
            'submissions' => $submissions
        ]);
    }

    /**
     * Show details for a specific user submission
     */
    public function showSubmission($userId)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        $user = User::findOrFail($userId);
        $company = CompanyInformation::where('user_id', $userId)->first();
        $employees = EmployeeInformation::where('user_id', $userId)->get();
        $devices = DeviceInformation::where('user_id', $userId)->get();
        $licenses = License::where('user_id', $userId)->get();
        $branches = Branch::where('user_id', $userId)->get();
        $webpage = CompanyDetails::where('user_id', $userId)->first();

        // dd($licenses);

        // Get attachments from session or database
        $employee_attachments = session('employee_attachments') ?? [];
        $device_attachments = session('device_attachments') ?? [];
        $license_attachments = session('license_attachments') ?? [];
        $webpage_attachments = session('webpage_attachments') ?? [];

        // Make sure attachments are arrays
        if (!is_array($employee_attachments)) $employee_attachments = [$employee_attachments];
        if (!is_array($device_attachments)) $device_attachments = [$device_attachments];
        if (!is_array($license_attachments)) $license_attachments = [$license_attachments];
        if (!is_array($webpage_attachments)) $webpage_attachments = [$webpage_attachments];

        return view('admin.submission-details', [
            'user' => $user,
            'company' => $company,
            'employees' => $employees,
            'devices' => $devices,
            'licenses' => $licenses,
            'branches' => $branches,
            'webpage' => $webpage,
            'employee_attachments' => array_filter($employee_attachments),
            'device_attachments' => array_filter($device_attachments),
            'license_attachments' => array_filter($license_attachments),
            'webpage_attachments' => array_filter($webpage_attachments),
        ]);
    }

    /**
     * Approve or reject a user submission
     */
    public function updateStatus(Request $request, $userId)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $company = CompanyInformation::where('user_id', $userId)->first();

        if ($company) {
            // Get the previous status for comparison
            $previousStatus = $company->approval_status;

            // Update company information
            $company->approval_status = $request->status;
            $company->admin_feedback = $request->feedback;
            $company->approved_by = Auth::id();
            $company->approved_at = now();
            $company->save();

            // If status is changed to approved, sync data to IT Glue
            if ($request->status === 'approved' && $previousStatus !== 'approved') {
                try {
                    Log::info('Starting IT Glue sync for user', ['user_id' => $userId]);

                    // Use the IT Glue service to sync data directly
                    $syncResult = $this->itglueService->syncApprovedSubmission($userId);

                    // Log the result
                    if ($syncResult['success']) {
                        Log::info('IT Glue sync completed successfully', [
                            'user_id' => $userId,
                            'organization_id' => $syncResult['organization_id'] ?? null,
                        ]);
                    } else {
                        Log::error('IT Glue sync failed', [
                            'user_id' => $userId,
                            'message' => $syncResult['message'],
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception during IT Glue sync', [
                        'user_id' => $userId,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            return redirect()->route('admin.submissions')->with('success', 'Submission status updated successfully');
        }

        return redirect()->route('admin.submissions')->with('error', 'Company information not found');
    }

    /**
     * User dashboard to view their submission status
     */
    public function userDashboard()
    {
        $userId = Auth::id();

        $company = CompanyInformation::where('user_id', $userId)->first();
        $employees = EmployeeInformation::where('user_id', $userId)->count();
        $devices = DeviceInformation::where('user_id', $userId)->count();
        $licenses = License::where('user_id', $userId)->count();
        $branches = Branch::where('user_id', $userId)->count();

        $status = $company ? $company->approval_status : 'pending';
        $feedback = $company ? $company->admin_feedback : null;

        $completionStatus = [
            'company' => $company ? true : false,
            'employees' => $employees > 0,
            'devices' => $devices > 0,
            'licenses' => $licenses > 0,
            'branches' => $branches > 0,
        ];

        $completionPercentage = array_sum($completionStatus) / count($completionStatus) * 100;

        return view('user.dashboard', [
            'company' => $company,
            'counts' => [
                'employees' => $employees,
                'devices' => $devices,
                'licenses' => $licenses,
                'branches' => $branches,
            ],
            'status' => $status,
            'feedback' => $feedback,
            'completionStatus' => $completionStatus,
            'completionPercentage' => $completionPercentage,
        ]);
    }
}
