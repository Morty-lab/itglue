<?php

namespace App\Services;

use App\Models\CompanyInformation;
use App\Models\EmployeeInformation;
use App\Models\DeviceInformation;
use App\Models\License;
use App\Models\Branch;
use App\Models\CompanyDetails;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use function config;

class ITGlueService
{
    protected $baseUrl;
    protected $apiKey;
    protected $headers;

    public function __construct()
    {
        $this->baseUrl = 'https://api.itglue.com';
        $this->apiKey = 'ITG.845bddab0ba96761aadce0ff9055db1a.7AXrVIJOWmOX56_jWOBrsFLrBxAiA3nfQKzQmodSBZpVFOHxsGaJgX3x2RJxs9ye';

        // Log API key info for debugging (remove in production)
        Log::info('IT Glue API Key Debug', [
            'api_key_exists' => !empty($this->apiKey),
            'api_key_length' => $this->apiKey ? strlen($this->apiKey) : 0,
            'first_10_chars' => $this->apiKey ? substr($this->apiKey, 0, 10) . '...' : 'N/A',
        ]);

        $this->headers = [
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/vnd.api+json',
        ];
    }

    /**
     * Test the API connection to IT Glue
     *
     * @return array Response information
     */
    public function testConnection()
    {
        try {
            Log::info('Testing IT Glue API connection', [
                'url' => "{$this->baseUrl}/organizations",
                'headers' => array_keys($this->headers),
            ]);

            $response = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/organizations", [
                    'page[size]' => 1,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful',
                    'status_code' => $response->status(),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Connection failed',
                    'status_code' => $response->status(),
                    'response' => $response->json(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception testing IT Glue connection', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Sync approved submission data to IT Glue
     *
     * @param int $userId The user ID whose submission was approved
     * @return array Response information
     */
    public function syncApprovedSubmission($userId)
    {
        try {
            Log::info('Starting IT Glue sync for user', ['user_id' => $userId]);

            // First test the connection
            $testResult = $this->testConnection();
            if (!$testResult['success']) {
                Log::error('IT Glue API connection test failed before sync', $testResult);
                return [
                    'success' => false,
                    'message' => 'Failed to connect to IT Glue API: ' . ($testResult['message'] ?? 'Unknown error'),
                ];
            }

            // Get all the required data
            $company = CompanyInformation::where('user_id', $userId)->firstOrFail();
            $employees = EmployeeInformation::where('user_id', $userId)->get();
            $devices = DeviceInformation::where('user_id', $userId)->get();
            $licenses = License::where('user_id', $userId)->get();
            $branches = Branch::where('user_id', $userId)->get();
            $webpage = CompanyDetails::where('user_id', $userId)->first();

            // Create the organization in IT Glue
            $organizationId = $this->createOrganization($company);

            Log::info('Organization ID in IT Glue', ['organization_id' => $organizationId]);


            if (!$organizationId) {
                return [
                    'success' => false,
                    'message' => 'Failed to create organization in IT Glue',
                ];
            }

            // Create contacts (employees)
            foreach ($employees as $employee) {
                Log::info('Creating contact in IT Glue', ['employee_id' => $employee->id, 'employee_name' => $employee->firstname . ' ' . $employee->lastname]);
                $this->createContact($employee, $organizationId);
            }

            // Create configuration items (devices)
            foreach ($devices as $device) {
                $this->createConfiguration($device, $organizationId);
            }

            // Create passwords
            if ($webpage) {
                $this->createPassword($webpage, $organizationId);
            }

            // Create locations (branches)
            foreach ($branches as $branch) {
                $this->createLocation($branch, $organizationId);
            }

            // Create licenses
            foreach ($licenses as $license) {
                $this->createLicense($license, $organizationId);
            }

            Log::info('IT Glue sync completed successfully for user', ['user_id' => $userId]);

            return [
                'success' => true,
                'message' => 'Data successfully synced to IT Glue',
                'organization_id' => $organizationId,
            ];
        } catch (\Exception $e) {
            Log::error('IT Glue sync failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'IT Glue sync failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Create an organization in IT Glue
     *
     * @param CompanyInformation $company
     * @return int|null The organization ID if successful, null otherwise
     */
    protected function createOrganization($company)
    {
        try {
            // Ensure there's a valid name - use company ID as fallback if name is missing
            $companyName = trim($company->name) ?: 'Company ' . $company->id;

            // Log the company name for debugging
            Log::info('Company name for IT Glue', [
                'company_id' => $company->id,
                'name_in_db' => $company->name,
                'name_being_used' => $companyName,
            ]);

            // Prepare request data with guaranteed name
            $requestData = [
                'data' => [
                    'type' => 'organizations',
                    'attributes' => [
                        'name' => $companyName,
                        'description' => $company->description ?? '',
                        'address_1' => $company->address ?? '',
                        'city' => $company->city ?? '',
                        'state' => $company->state ?? '',
                        'zip' => $company->postal_code ?? '',
                        'country' => $company->country ?? '',
                        'phone' => $company->phone ?? '',
                        'website' => $company->website ?? '',
                        'notes' => $company->notes ?? '',
                    ],
                ],
            ];

            Log::info('Creating organization in IT Glue', [
                'company_id' => $company->id,
                'url' => "{$this->baseUrl}/organizations",
                'headers' => array_keys($this->headers),
                'request_data' => $requestData,
            ]);

            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/organizations", $requestData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Organization created in IT Glue', [
                    'organization_id' => $data['data']['id'],
                    'company_id' => $company->id,
                ]);
                return $data['data']['id'];
            } else {
                Log::error('Failed to create organization in IT Glue', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'company_id' => $company->id,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception creating organization in IT Glue', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'company_id' => $company->id,
            ]);
            return null;
        }
    }
    /**
     * Create a contact in IT Glue
     *
     * @param EmployeeInformation $employee
     * @param int $organizationId
     * @return int|null The contact ID if successful, null otherwise
     */
    protected function createContact($employee, $organizationId)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/contacts", [
                    'data' => [
                        'type' => 'contacts',
                        'attributes' => [
                            'organization_id' => $organizationId,
                            'first-name' => $employee->firstname,
                            'last-name' => $employee->lastname,
                            'title' => $employee->employee_title ?? '',
                            'email' => $employee->employee_email ?? '',
                            'phone' => $employee->employee_phone_number ?? '',
                            'notes' => $employee->notes ?? '',
                        ],
                        'relationships' => [
                            'organization' => [
                                'data' => [
                                    'type' => 'organizations',
                                    'id' => $organizationId,
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Contact created in IT Glue', [
                    'contact_id' => $data['data']['id'],
                    'employee_id' => $employee->id,
                ]);
                return $data['data']['id'];
            } else {
                Log::error('Failed to create contact in IT Glue', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception creating contact in IT Glue', [
                'error' => $e->getMessage(),
                'employee_id' => $employee->id,
            ]);
            return null;
        }
    }

    /**
     * Create a configuration in IT Glue
     *
     * @param DeviceInformation $device
     * @param int $organizationId
     * @return int|null The configuration ID if successful, null otherwise
     */
    protected function createConfiguration($device, $organizationId)
    {
        try {
            // First, we need to determine the configuration type ID
            // For this example, we'll use a generic device type
            $configTypeId = $this->getConfigurationTypeId($device->device_type ?? 'Device');

            if (!$configTypeId) {
                // Create a new configuration type if it doesn't exist
                $configTypeId = $this->createConfigurationType($device->device_type ?? 'Device');
                if (!$configTypeId) {
                    return null;
                }
            }

            Log::info('Configuration Type ID obtained', [
                'config_type_id' => $configTypeId,
                'device_type' => $device->device_type ?? 'Device',
            ]);

            // /organizations/{$organizationId}/relationships
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/configurations",
                [
                    'data' => [
                        'type' => 'configurations',
                        'attributes' => [
                            'name' => $device->device_type ?? 'Device',
                            'model-number' => $device->model ?? '',
                            'serial-number' => $device->serial_number ?? '',
                            'notes' => $device->notes ?? '',
                            'primary-ip' => $device->device_ip_address ?? '',
                            'hostname' => $device->hostname ?? '',
                            'asset-tag' => $device->asset_tag ?? '',
                        ],
                        'relationships' => [
                            'organization' => [
                                'data' => [
                                    'type' => 'organizations',
                                    'id' => $organizationId,
                                ],
                            ],
                            'configuration-type' => [
                                'data' => [
                                    'type' => 'configuration-types',
                                    'id' => $configTypeId,
                                ],
                            ],
                        ],
                    ],
                ]
                // [
                //     'data' => [
                //         'type' => 'configurations',
                //         'attributes' => [
                //             'name' => $device->device_type ?? 'Device',
                //             'configuration-type-id' => $configTypeId,
                //             'configuration-status-id' => $device->configuration_status_id ?? 2, // Defaulting to 2 if not provided
                //             'manufacturer-id' => $device->manufacturer_id ?? null,
                //             'model-id' => $device->model_id ?? null,
                //             'operating-system-id' => $device->operating_system_id ?? null,
                //             'location-id' => $device->location_id ?? null,
                //         ],
                //         'relationships' => [

                //             'configuration_interfaces' => [
                //                 'data' => [
                //                     [
                //                         'type' => 'configuration_interfaces',
                //                         'attributes' => [
                //                             'name' => $device->device_type ?? 'Device',
                //                             'ip-address' => $device->device_ip_address ?? '',
                //                         ],
                //                     ],
                //                 ],
                //             ],
                //         ],
                //     ],
                // ]
                );

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Configuration created in IT Glue', [
                    'configuration_id' => $data['data']['id'],
                    'device_id' => $device->id,
                ]);
                return $data['data']['id'];
            } else {
                // $response = Http::withHeaders($this->headers)
                //     ->get("{$this->baseUrl}/configuration_types");

                // Log::info('Available Configuration Types', ['response' => $response->json()]);

                Log::error('Failed to create configuration in IT Glue', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'device_id' => $device->id,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception creating configuration in IT Glue', [
                'error' => $e->getMessage(),
                'device_id' => $device->id,
            ]);
            return null;
        }
    }

    /**
     * Get the configuration type ID from IT Glue
     *
     * @param string $name
     * @return int|null The configuration type ID if found, null otherwise
     */
    protected function getConfigurationTypeId($name)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/configuration_types", [
                    'filter[name]' => $name,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']) && !empty($data['data'])) {
                    return $data['data'][0]['id'];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Exception getting configuration type from IT Glue', [
                'error' => $e->getMessage(),
                'name' => $name,
            ]);
            return null;
        }
    }

    /**
     * Create a configuration type in IT Glue
     *
     * @param string $name
     * @return int|null The configuration type ID if successful, null otherwise
     */
    protected function createConfigurationType($name)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/configuration_types", [
                    'data' => [
                        'type' => 'configuration-types',
                        'attributes' => [
                            'name' => $name,
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Configuration type created in IT Glue', [
                    'configuration_type_id' => $data['data']['id'],
                    'name' => $name,
                ]);
                return $data['data']['id'];
            } else {
                Log::error('Failed to create configuration type in IT Glue', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'name' => $name,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception creating configuration type in IT Glue', [
                'error' => $e->getMessage(),
                'name' => $name,
            ]);
            return null;
        }
    }

    /**
     * Create a password in IT Glue
     *
     * @param CompanyDetails $webpage
     * @param int $organizationId
     * @return int|null The password ID if successful, null otherwise
     */
    protected function createPassword($webpage, $organizationId)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/passwords", [
                    'data' => [
                        'type' => 'passwords',
                        'attributes' => [
                            'organization_id' => $organizationId,
                            'name' => 'Website Login',
                            'username' => $webpage->credential_username ?? '',
                            'password' => $webpage->credential_password ?? '',
                            'url' => $webpage->credential_url ?? '',
                            'notes' => $webpage->credential_notes ?? '',
                        ],
                        'relationships' => [
                            'organization' => [
                                'data' => [
                                    'type' => 'organizations',
                                    'id' => $organizationId,
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Password created in IT Glue', [
                    'password_id' => $data['data']['id'],
                    'webpage_id' => $webpage->id,
                ]);
                return $data['data']['id'];
            } else {
                Log::error('Failed to create password in IT Glue', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'webpage_id' => $webpage->id,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception creating password in IT Glue', [
                'error' => $e->getMessage(),
                'webpage_id' => $webpage->id,
            ]);
            return null;
        }
    }

    /**
     * Create a location in IT Glue
     *
     * @param Branch $branch
     * @param int $organizationId
     * @return int|null The location ID if successful, null otherwise
     */
    protected function createLocation($branch, $organizationId)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/locations", [
                    'data' => [
                        'type' => 'locations',
                        'attributes' => [
                            'organization_id' => $organizationId,
                            'name' => $branch->name ?? 'Branch Office',
                            'address-1' => $branch->branch_address ?? '',
                            'city' => $branch->city ?? '',
                            'state' => $branch->state ?? '',
                            'postal-code' => $branch->postal_code ?? '',
                            'country' => $branch->country ?? '',
                            'phone' => $branch->phone_number ?? '',
                        ],
                        'relationships' => [
                            'organization' => [
                                'data' => [
                                    'type' => 'organizations',
                                    'id' => $organizationId,
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Location created in IT Glue', [
                    'location_id' => $data['data']['id'],
                    'branch_id' => $branch->id,
                ]);
                return $data['data']['id'];
            } else {
                Log::error('Failed to create location in IT Glue', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'branch_id' => $branch->id,
                    'organization_id' => $organizationId,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception creating location in IT Glue', [
                'error' => $e->getMessage(),
                'branch_id' => $branch->id,
            ]);
            return null;
        }
    }

    /**
     * Create a flexible asset for licenses in IT Glue
     *
     * @param License $license
     * @param int $organizationId
     * @return int|null The flexible asset ID if successful, null otherwise
     */
    protected function createLicense($license, $organizationId)
    {
        try {
            // First check if a flexible asset type for licenses exists
            $flexibleAssetTypeId = $this->getFlexibleAssetTypeId('License');

            if (!$flexibleAssetTypeId) {
                // Create a new flexible asset type for licenses
                $flexibleAssetTypeId = $this->createFlexibleAssetType('License');
                if (!$flexibleAssetTypeId) {
                    return null;
                }
            }

            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/flexible_assets", [
                    'data' => [
                        'type' => 'flexible-assets',
                        'attributes' => [
                            'traits' => [
                                'name' => $license->name ?? 'License',
                                'license-key' => $license->license_key ?? '',
                                'expiration-date' => $license->expiration_date ?? '',
                                'type' => $license->type ?? '',
                                'seats' => $license->seats ?? '',
                                'cost' => $license->cost ?? '',
                                'notes' => $license->notes ?? '',
                            ],
                        ],
                        'relationships' => [
                            'organization' => [
                                'data' => [
                                    'type' => 'organizations',
                                    'id' => $organizationId,
                                ],
                            ],
                            'flexible-asset-type' => [
                                'data' => [
                                    'type' => 'flexible-asset-types',
                                    'id' => $flexibleAssetTypeId,
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('License created in IT Glue', [
                    'flexible_asset_id' => $data['data']['id'],
                    'license_id' => $license->id,
                ]);
                return $data['data']['id'];
            } else {
                Log::error('Failed to create license in IT Glue', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'license_id' => $license->id,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception creating license in IT Glue', [
                'error' => $e->getMessage(),
                'license_id' => $license->id,
            ]);
            return null;
        }
    }

    /**
     * Get the flexible asset type ID from IT Glue
     *
     * @param string $name
     * @return int|null The flexible asset type ID if found, null otherwise
     */
    protected function getFlexibleAssetTypeId($name)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->get("{$this->baseUrl}/flexible_asset_types", [
                    'filter[name]' => $name,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']) && !empty($data['data'])) {
                    return $data['data'][0]['id'];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Exception getting flexible asset type from IT Glue', [
                'error' => $e->getMessage(),
                'name' => $name,
            ]);
            return null;
        }
    }

    /**
     * Create a flexible asset type in IT Glue
     *
     * @param string $name
     * @return int|null The flexible asset type ID if successful, null otherwise
     */
    protected function createFlexibleAssetType($name)
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->post("{$this->baseUrl}/flexible_asset_types", [
                    'data' => [
                        'type' => 'flexible-asset-types',
                        'attributes' => [
                            'name' => $name,
                            'icon' => 'key',
                            'description' => 'License information',
                            'traits' => [
                                [
                                    'name' => 'name',
                                    'kind' => 'text',
                                    'required' => true,
                                    'show_in_list' => true,
                                ],
                                [
                                    'name' => 'license-key',
                                    'kind' => 'password',
                                    'required' => false,
                                    'show_in_list' => false,
                                ],
                                [
                                    'name' => 'expiration-date',
                                    'kind' => 'date',
                                    'required' => false,
                                    'show_in_list' => true,
                                ],
                                [
                                    'name' => 'type',
                                    'kind' => 'text',
                                    'required' => false,
                                    'show_in_list' => true,
                                ],
                                [
                                    'name' => 'seats',
                                    'kind' => 'number',
                                    'required' => false,
                                    'show_in_list' => true,
                                ],
                                [
                                    'name' => 'cost',
                                    'kind' => 'text',
                                    'required' => false,
                                    'show_in_list' => false,
                                ],
                                [
                                    'name' => 'notes',
                                    'kind' => 'textbox',
                                    'required' => false,
                                    'show_in_list' => false,
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Flexible asset type created in IT Glue', [
                    'flexible_asset_type_id' => $data['data']['id'],
                    'name' => $name,
                ]);
                return $data['data']['id'];
            } else {
                Log::error('Failed to create flexible asset type in IT Glue', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'name' => $name,
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception creating flexible asset type in IT Glue', [
                'error' => $e->getMessage(),
                'name' => $name,
            ]);
            return null;
        }
    }
}
