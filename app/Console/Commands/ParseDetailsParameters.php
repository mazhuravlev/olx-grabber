<?php

namespace App\Console\Commands;

use App\Models\DetailsParameter;
use App\Models\Offer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

class ParseDetailsParameters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'details:parameters:parse {--info}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse details parameters from offers';


    private $enableInfo;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->enableInfo = $this->option('info');
        $cache = [];
        $bar = $this->enableInfo ? null : $bar = $this->output->createProgressBar(Offer::count());
        $offers = Offer::query()->chunk(100, function (Collection $offers) use ($bar, &$cache) {
            $this->info("Got {$offers->count()} offers");
            foreach ($offers as $offer) {
                if ($bar) {
                    $bar->advance();
                }
                foreach ($offer->details as $key => $value) {
                    if (array_key_exists($key, $cache)) {
                        if (array_key_exists($value, $cache[$key])) {
                            $this->info("$key:$value exists in cache");
                            continue;
                        }
                    } else {
                        $cache[$key] = [];
                    }
                    $detailsParameter = DetailsParameter::firstOrCreate(
                        ['parameter' => $key]
                    );
                    try {
                        $detailsValue = $detailsParameter->detailsValues()
                            ->firstOrCreate(
                                ['value' => $value]
                            );
                        if ($detailsValue->wasRecentlyCreated) {
                            array_push($cache[$key], $value);
                            $this->info("Added $key:$value");
                        }
                    } catch (QueryException $e) {
                        if (23000 !== intval($e->getCode())) {
                            throw $e;
                        } else {
                            $this->info("Skipped $key:$value");
                        }
                    }
                }
            }
        });
    }

    public function info($string, $verbosity = null)
    {
        if ($this->enableInfo) {
            parent::info($string, $verbosity);
        }
    }
}
