<?php

namespace App\Console\Commands;

use App\Jobs\ExportOffer;
use App\Models\Offer;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ExportCommand extends Command
{

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:offer {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export offer by OLX id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $offer = Offer::where(['olx_id' => $this->argument('id')])->first();
        if (!$offer) {
            $this->error('Offer not found: ' . $this->argument('id'));
            return;
        }
        $job = (new ExportOffer($offer))->onQueue('export_offers');
        $this->dispatch($job);
        $this->info('Export job created');
    }
}
