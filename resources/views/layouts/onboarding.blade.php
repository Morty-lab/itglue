<!DOCTYPE html>
<html lang="en" data-bs-theme="" id="htmlPage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unified Technician Onboarding Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/onboarding.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>


</head>

<body>


    <div class="container-fluid p-0">
        <div class="row min-vh-100 m-0">
            <!-- Left Side (Background Image) -->
            <div class="col-md-4 p-0 d-none d-md-flex align-items-center justify-content-center"
                style="background: url({{ asset('images/photo.jpg') }}) center center/cover no-repeat;">
                <div class="position-relative top-0 start-0 w-100 h-100 bg-black opacity-50"></div>
            </div>


            <!-- Right Side (Content) -->
            <div class="col-md-8 p-5">
                <div class="d-flex justify-content-between">
                    <img src={{ asset('images/logo.png') }} class="w-25" alt="">

                    <div class="toggle-button">
                        <!-- Dark/Light Toggle Button -->
                        <input type="checkbox" class="checkbox" id="checkbox">
                        <label for="checkbox" class="checkbox-label">
                            <i class="fas fa-moon"></i>
                            <i class="fas fa-sun"></i>
                            <span class="ball"></span>
                        </label>
                    </div>
                </div>
                <h3 class="fw-bold mt-3">ONBOARDING FORM</h3>


                <!-- Steps Indicator -->
                @php
                    $steps = [
                        '/' => 'Company Information',
                        'onboarding.contact_information' => 'Contact Information',
                        'onboarding.physical_devices' => 'Physical Devices',
                        'onboarding.webpage_development' => 'Webpage & Document',
                        'onboarding.software_licenses' => 'Software Licensing',
                    ];
                    $currentStep = Route::currentRouteName(); // Get the current route
                    $stepKeys = array_keys($steps);
                @endphp

                <div class="text-center mt-3">
                    <div class="step-container">
                        @foreach ($steps as $route => $label)

                            @php
                                $currentIndex = array_search($currentStep, $stepKeys);
                                $stepIndex = array_search($route, $stepKeys);

                                $statusClass = '';
                                if ($stepIndex < $currentIndex) {
                                    $statusClass = 'completed'; // Previous steps
                                } elseif ($stepIndex == $currentIndex) {
                                    $statusClass = 'active'; // Current step
                                } else {
                                    $statusClass = 'pending'; // Future steps
                                }
                            @endphp




                            <div class="step-wrapper {{ $statusClass }}">
                                <div class="step">
                                    <i
                                        class="step-icon bi bi-{{ $statusClass == 'completed' ? 'check-circle-fill text-success' : ($statusClass == 'active' ? 'circle-fill text-primary' : 'circle text-secondary') }} fs-5"></i>
                                    <span
                                        class="text-xs mt-2 {{ $statusClass == 'active' ? 'font-bold text-primary' : 'text-secondary' }}">{{ $label }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                @yield('content')




            </div>
        </div>

        <script src="{{ asset('js/script.js') }}"></script>
        <script src="{{ asset('js/onboarding.js') }}"></script>
        <script src="{{ asset('js/company_info.js') }}"></script>
        <script src="{{ asset('js/contact_info.js') }}"></script>
        <script src="{{ asset('js/physical_devices.js') }}"></script>
        <script src="{{ asset('js/web_doc.js') }}"></script>
        <script src="{{ asset('js/software_licenses.js') }}"></script>

</body>

</html>
