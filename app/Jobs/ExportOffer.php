<?php

namespace App\Jobs;

use App\Models\Offer;
use App\Models\Phone;
use App\System\Realtnavi\Export;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ExportOffer extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $offer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    private static function addToBanlist(Connection $connection, array $phones)
    {
        $count = 0;
        foreach ($phones as $phone) {
            try {
                $connection->table('agentBans')
                    ->insert(
                        [
                            'name' => 'Бизнес',
                            'phone' => $phone
                        ]
                    );
                $count++;
            } catch (QueryException $e) {
                if (23000 !== intval($e->getCode())) {
                    throw $e;
                }
            }
        }
        return $count;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (0 === $this->offer->phones()->count()) {
            echo "offer {$this->offer->olx_id} has no phones\n";
            return;
        }
        if ($location = $this->offer->location()->first() and $region = $location->region and $this->offer->phones()->count() > 0) {
            $connection = DB::connection('grabber_' . $region);
            foreach ($this->offer->phones()->get() as $phone) {
                /** @var Phone $phone */
                if ($phone->offers()->count() >= env('AGENT_OFFER_COUNT_THRESHOLD')) {
                    self::addToBanlist($connection, [$phone->id]);
                    echo "agent offer count threshold exceeded for {$phone->id}, added to ban list\n";
                }
            }
            if (self::phonesBanned($connection, $this->offer->phones()->get()->pluck('id')->toArray())) {
                echo $this->offer->olx_id . " has banned phone\n";
                return;
            }
            if ($this->inAnyTable($connection, $this->offer->olx_id, ['adDetails', 'adDeleted', 'adDontShow', 'adDuplicates'])) {
                echo "offer exists in db ($region): " . $this->offer->olx_id . PHP_EOL;
                return;
            }
            $offerId = null;
            /** @var Offer $this ->offer */
            $exportedOffer = Export::export($this->offer);
            if (array_key_exists('_agent', $exportedOffer)) {
                $bannedCount = self::addToBanlist($connection, $this->offer->phones()->get()->pluck('id')->toArray());
                echo "added $bannedCount phones to banlist: " . $this->offer->olx_id . PHP_EOL;
                return;
            }
            try {
                $offerId = $connection->table('adDetails')->insertGetId($exportedOffer);
            } catch (QueryException $e) {
                if (23000 === intval($e->getCode())) {
                    echo 'offer exists in db (duplicate key error): ' . $this->offer->olx_id . PHP_EOL;
                    return;
                } else {
                    throw $e;
                }
            }
            if (is_null($offerId)) {
                echo "offer id cant be null\n";
                Log::critical('Failed to insert offer', (array)$this->offer);
                $this->release(100);
            } else {
                echo $this->offer->olx_id . " offer inserted to db: $offerId ($region)\n";
            }
            foreach ($this->offer->photos()->get() as $photo) {
                $connection->table('adImages')
                    ->insert(
                        $image = [
                            'adId' => $offerId,
                            'url' => $photo->url,
                        ]
                    );
            }
        }
    }

    private static function phonesBanned(Connection $connection, array $phones)
    {
        $count = $connection->table('agentBans')
            ->whereIn('phone', $phones)
            ->count();
        return $count > 0;
    }

    private function inAnyTable(Connection $connection, $olxId, array $tables)
    {
        foreach ($tables as $table) {
            $count = $connection->table($table)
                ->where('orig_id', $olxId)
                ->where('source', 'OLX')
                ->count();
            if ($count > 0) {
                return true;
            }
        }
        return false;
    }

}
