<?php

namespace App\Console\Commands;

use App\Models\DetailsParameter;
use App\Models\Offer;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class ParseDetailsParameters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'details:parameters:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse details parameters from offers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $offers = Offer::query()->get(['details']);
        foreach ($offers as $offer) {
            foreach ($offer->details as $key => $value) {
                $detailsParameter = DetailsParameter::firstOrCreate(
                    ['parameter' => $key]
                );
                try {
                    $detailsValue = $detailsParameter->detailsValues()
                        ->firstOrCreate(
                            ['value' => $value]
                        );
                } catch (QueryException $e) {
                    if (23000 !== intval($e->getCode())) {
                        throw $e;
                    }
                }
            }
        }
    }
}
