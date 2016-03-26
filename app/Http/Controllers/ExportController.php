<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

class ExportController extends Controller
{
    function index()
    {
        return view('export')->with([
            'regions' => Region::where('id', '<>', '---')->get(),
        ]);
    }

    function export(Request $request)
    {
        $regions = $request->get('regions');
        $daysCount = $request->get('day_count');
        $date = Carbon::createFromTimestamp(time() - $daysCount * 86400);
        $offers = Offer::query()->where('created_at', '>', $date)->get();
        echo '<ul>';
        /** @var \Illuminate\Bus\Dispatcher $dispatcher */
        $dispatcher = app('Illuminate\Bus\Dispatcher');
        foreach ($offers as $offer) {
            /** @var Offer $offer */
            if ($location = $offer->location and in_array($location->region, $regions, true)) {
                $dispatcher->dispatch(
                    (new \App\Jobs\ExportOffer($offer))
                        ->onQueue('export_offers')
                );
                echo "<li>created export job for <a href='/offer/'{$offer->id}'>{$offer->id}</a></li>";
            }
        }
        echo '</ul><p>done</p>';
    }
}
