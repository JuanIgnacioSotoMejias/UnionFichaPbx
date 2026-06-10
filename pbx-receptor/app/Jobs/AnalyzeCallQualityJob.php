<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Services\IntelligenceService;

class AnalyzeCallQualityJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly array $callData)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(IntelligenceService $intelligence): void
    {
        $intelligence->analyzeCall($this->callData);
    }
}
