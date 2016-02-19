<?php

namespace App\System\Realtnavi;


use App\Models\Offer;

class Export
{

    public static function export(Offer $offer)
    {
        $result = [
            'cat' => Categories::getCat(preg_replace('/^nedvizhimost\//', '', $offer->cat_path)),
            'orig_id' => $offer->olx_id,
            'date_grabbed' => time(),
            'date_added' => time(),
            'href' => $offer->href,
            'title' => $offer->title,
            'description' => $offer->description,
            'price' => $offer->price_string,
            'thumbnail' => self::getThumbnail($offer),
            'cityDetails' => self::getLocation($offer),
            'source' => 'OLX',
            'phone' => self::getPhones($offer),
        ];
        $details = $offer->details;
        if (is_array($details) and array_key_exists('Объявление от', $details) and 'Бизнес' == $details['Объявление от']) {
            $result['_agent'] = true;
        }

        return $result;
    }

    private static function getLocation(Offer $offer)
    {
        if ($location = $offer->location()->first()) {
            return $location->location;
        } else {
            return null;
        }
    }

    private static function getPhones(Offer $offer)
    {
        if ($offer->phones) {
            return implode(
                ', ',
                $offer->phones()
                    ->get()
                    ->pluck('id')
                    ->toArray()
            );
        } else {
            return null;
        }
    }

    private static function getThumbnail(Offer $offer)
    {
        if ($photo = $offer->photos()->first()) {
            return $photo->url;
        } else {
            return null;
        }
    }


}