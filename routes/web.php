<?php

use App\Http\Controllers\OnboardingForm;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\SubmissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CompanyInformationController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\CompanyDetailsController;
// Default route
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Onboarding form routes
Route::middleware('auth')->group(function () {
    Route::get('/', [OnboardingForm::class, 'index'])->name('onboarding');
    Route::post('/OnboardingForm', [OnboardingForm::class, 'store'])->name('OnboardingForm.store');

    Route::post('/branches', [OnboardingForm::class, 'store_company_details'])->name('branches.store');
    Route::get('/onboarding/contact-information', [OnboardingForm::class, 'contact_information'])->name('onboarding.contact_information');
    Route::get('/onboarding/physical-devices', [OnboardingForm::class, 'physical_devices'])->name('onboarding.physical_devices');
    Route::get('/onboarding/webpage-development', [OnboardingForm::class, 'webpage_development'])->name('onboarding.webpage_development');
    Route::get('/onboarding/software-licenses', [OnboardingForm::class, 'software_licenses'])->name('onboarding.software_licenses');
    Route::post('/onboarding/software-licenses', [OnboardingForm::class, 'store_software_license'])->name('onboarding.software_licenses.store');
    Route::post('/onboarding/contact-information/employees', [OnboardingForm::class, 'store_employees'])->name('onboarding.contact_information.employees.store');
    Route::post('/onboarding/physical-devices/devices', [OnboardingForm::class, 'store_devices'])->name('onboarding.physical_devices.devices.store');
    Route::post('/onboarding/webpage-development/webpage-development', [OnboardingForm::class, 'store_webpage_development'])->name('onboarding.webpage_development.webpage-development.store');
});

// User Dashboard
Route::get('/dashboard', [DashboardController::class, 'userDashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

// Admin Dashboard and Submissions Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/submissions', [DashboardController::class, 'index'])->name('admin.submissions');
    Route::get('/submissions/{id}', [DashboardController::class, 'showSubmission'])->name('admin.submission.show');
    Route::put('/submissions/{id}', [DashboardController::class, 'updateStatus'])->name('admin.submission.update');
    Route::put('/company/update-multiple-fields', [CompanyInformationController::class, 'updateMultipleFields'])->name('admin.company.update-multiple-fields');
    // Route::put('/company/update-multiple-fields', [CompanyController::class, 'updateMultiple'])->name('admin.company.update-multiple-fields');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Branch routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('/branches/{id}', [BranchController::class, 'show'])->name('branches.show');
    Route::get('/branches/{id}/edit', [BranchController::class, 'edit'])->name('branches.edit');
    // Route::patch('/branches/{id}', [BranchController::class, 'updateAll'])->name('branches.updateAll');
    Route::patch('/branches/updateAll', [BranchController::class, 'updateAll'])->name('branches.updateAll'); // Add this line
    Route::delete('/branches/{id}', [BranchController::class, 'destroy'])->name('branches.destroy');
});


// Device routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/{id}', [DeviceController::class, 'show'])->name('devices.show');
    Route::get('/devices/{id}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
    Route::patch('/devices/updateAll', [DeviceController::class, 'updateAll'])->name('devices.updateAll'); // Add this line
    Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
});

// Employee routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::patch('/employees/updateAll', [EmployeeController::class, 'updateAll'])->name('employees.updateAll'); // Add this line
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('/admin/logs', [App\Http\Controllers\LogViewerController::class, 'index'])
    ->name('admin.logs')
    ->middleware(['auth']);
});

// License routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
    Route::get('/licenses/{id}', [LicenseController::class, 'show'])->name('licenses.show');
    Route::get('/licenses/{id}/edit', [LicenseController::class, 'edit'])->name('licenses.edit');
    Route::patch('/licenses/updateAll', [LicenseController::class, 'updateAll'])->name('licenses.updateAll'); // Add this line
    Route::delete('/licenses/{id}', [LicenseController::class, 'destroy'])->name('licenses.destroy');
});

//Webpage routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/webpages', [CompanyDetailsController::class, 'index'])->name('webpages.index');
    Route::get('/webpages/{id}', [CompanyDetailsController::class, 'show'])->name('webpages.show');
    Route::get('/webpages/{id}/edit', [CompanyDetailsController::class, 'edit'])->name('webpages.edit');
    Route::put('/webpages/update-multiple-fields', [CompanyDetailsController::class, 'updateMultipleFields'])->name('webpages.update-multiple-fields');
    Route::delete('/webpages/{id}', [CompanyDetailsController::class, 'destroy'])->name('webpages.destroy');
    Route::post('/credentials/{id}', [OnboardingForm::class, 'destroy_credentials'])->name('credentials.destroy');
});



// Replace your existing logs route with this

Route::get('/logs', function () {
    // Check if user is admin (optional - remove if not needed)
    if (!Auth::user() || Auth::user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access');
    }

    // Use the direct path you provided
    // $logPath = '/var/www/clients/client3/web65/web/storage/logs/laravel.log';
    $logPath = storage_path('logs/laravel.log');

    // Handle log clearing if requested
    if (request()->has('clear') && request('clear') == 1) {
        // Truncate the log file by opening it in write mode
        if (file_exists($logPath)) {
            file_put_contents($logPath, "Log file cleared at " . date('Y-m-d H:i:s') . "\n");
        }

        // Redirect back to the logs page
        return redirect('/logs')->with('message', 'Log file has been cleared.');
    }

    if (!file_exists($logPath)) {
        return response('Log file not found: ' . $logPath, 404);
    }

    // Get the file size for display
    $fileSize = round(filesize($logPath) / 1024 / 1024, 2); // in MB

    // Get the last 1000 lines of the log file (adjust as needed)
    $lines = 1000;
    $file = file($logPath);
    $file = array_slice($file, -$lines);
    $logs = implode('', $file);

    // Highlight different log types
    $logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?ERROR.*?)(\n|$)/i', '<span style="color: red;">$1</span>$2', $logs);
    $logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?WARNING.*?)(\n|$)/i', '<span style="color: orange;">$1</span>$2', $logs);
    $logs = preg_replace('/(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?INFO.*?)(\n|$)/i', '<span style="color: blue;">$1</span>$2', $logs);

    // Get flash message if present
    $message = session('message');
    $messageHtml = $message ? "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;'>{$message}</div>" : '';

    // Build the HTML directly in the route
    $html = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Laravel Logs</title>
        <style>
            body {
                font-family: monospace;
                padding: 20px;
                background-color: #f5f5f5;
            }
            pre {
                background-color: white;
                padding: 15px;
                border-radius: 5px;
                overflow-x: auto;
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            .controls {
                margin-bottom: 15px;
            }
            .btn {
                padding: 6px 12px;
                margin-right: 5px;
                border-radius: 4px;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
            }
            .btn-primary {
                background-color: #007bff;
                border: 1px solid #007bff;
                color: white;
            }
            .btn-danger {
                background-color: #dc3545;
                border: 1px solid #dc3545;
                color: white;
            }
            .file-info {
                margin-bottom: 10px;
                font-style: italic;
            }
            .confirm-dialog {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0.3);
                z-index: 1000;
            }
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
        </style>
    </head>
    <body>
        <h1>Laravel Logs</h1>

        {$messageHtml}

        <div class="controls">
            <button class="btn btn-primary" onclick="window.location.reload()">Refresh</button>
            <button class="btn btn-danger" onclick="showConfirmation()">Clear Logs</button>
        </div>

        <div class="file-info">
            Log file size: {$fileSize} MB | Showing last {$lines} lines
        </div>

        <pre>{$logs}</pre>

        <div class="overlay" id="overlay"></div>
        <div class="confirm-dialog" id="confirmDialog">
            <h3>Confirm Log Clearing</h3>
            <p>Are you sure you want to clear the log file? This cannot be undone.</p>
            <div>
                <button class="btn btn-danger" onclick="clearLogs()">Yes, Clear Logs</button>
                <button class="btn" onclick="hideConfirmation()">Cancel</button>
            </div>
        </div>

        <script>
            function showConfirmation() {
                document.getElementById('overlay').style.display = 'block';
                document.getElementById('confirmDialog').style.display = 'block';
            }

            function hideConfirmation() {
                document.getElementById('overlay').style.display = 'none';
                document.getElementById('confirmDialog').style.display = 'none';
            }

            function clearLogs() {
                window.location.href = '/logs?clear=1';
            }
        </script>
    </body>
    </html>
    HTML;

    return response($html);
});

require __DIR__.'/auth.php';
