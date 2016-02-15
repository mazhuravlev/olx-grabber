<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Phone;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePhoneOfferCount extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $phone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Phone $phone)
    {
        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->phone->offer_count = $this->phone->offers()->count();
        $this->phone->save();
    }
}
