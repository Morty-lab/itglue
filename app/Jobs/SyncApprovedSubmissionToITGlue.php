<?php

namespace App\Jobs;

use App\Services\ITGlueService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncApprovedSubmissionToITGlue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user ID.
     *
     * @var int
     */
    protected $userId;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @param \App\Services\ITGlueService $itglueService
     * @return void
     */
    public function handle(ITGlueService $itglueService)
    {
        Log::info('Starting IT Glue sync job for user', ['user_id' => $this->userId]);

        try {
            $result = $itglueService->syncApprovedSubmission($this->userId);

            if ($result['success']) {
                Log::info('IT Glue sync job completed successfully', [
                    'user_id' => $this->userId,
                    'organization_id' => $result['organization_id'] ?? null,
                ]);
            } else {
                Log::error('IT Glue sync job failed', [
                    'user_id' => $this->userId,
                    'message' => $result['message'],
                ]);

                // Optionally throw an exception to trigger a retry
                throw new \Exception($result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Exception in IT Glue sync job', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception to trigger a retry
            throw $e;
        }
    }

    /**
     * The job failed to process.
     *
     * @param \Exception $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::error('IT Glue sync job failed after all retries', [
            'user_id' => $this->userId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // You could add code here to notify an admin of the failure
        // For example, send an email or create a notification
    }
}
