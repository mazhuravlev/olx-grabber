<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\GrabbedUrl;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Parse extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GrabbedUrl $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GrabbedUrl $url)
    {

    }
}
