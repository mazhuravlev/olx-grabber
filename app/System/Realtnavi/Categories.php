<?php

namespace App\System\Realtnavi;


class Categories
{

    const CAT_FLAT_RENT_DAY = 1;
    const CAT_ROOM_RENT_DAY = 2;
    const CAT_HOUSE_RENT_DAY = 3;
    const CAT_COMMERCIAL_RENT_DAY = 4;
    const CAT_FLAT_RENT = 5;
    const CAT_ROOM_RENT = 6;
    const CAT_HOUSE_RENT = 7;
    const CAT_COMMERCIAL_RENT = 8;
    const CAT_FLAT_SALE = 9;
    const CAT_ROOM_SALE = 10;
    const CAT_HOUSE_SALE = 11;
    const CAT_LAND_W_ADDRESS_SALE = 12;
    const CAT_COMMERCIAL_SALE = 13;
    const CAT_NEW_FLAT = 14;
    const CAT_NEW_COMMERCIAL = 15;
    const CAT_DACHA_SELL = 16;
    const CAT_GARAGE_SELL = 17;
    const CAT_OTHER_OTHER = 18;
    const CAT_DACHA_RENT = 19;
    const CAT_GARAGE_RENT = 20;
    const CAT_NEW_FROM_BUILDER = 23;
    const CAT_LAND_UNFINISHED = 24;
    const CAT_LAND_DACHA = 27;
    const CAT_DACHA_RENT_DAY = 28;
    const CAT_LAND_FARM = 27;

    private static $cats =
        [
            'arenda-domov/dolgosrochnaya-arenda-domov' => self::CAT_HOUSE_RENT,
            'arenda-domov/doma-posutochno-pochasovo' => self::CAT_HOUSE_RENT_DAY,
            'arenda-garazhey-stoyanok' => self::CAT_GARAGE_RENT,
            'arenda-komnat/dolgosrochnaya-arenda-komnat' => self::CAT_ROOM_RENT,
            'arenda-komnat/komnaty-posutochno' => self::CAT_ROOM_RENT_DAY,
            'arenda-komnat/koyko-mesta' => self::CAT_ROOM_RENT,
            'arenda-kvartir/dolgosrochnaya-arenda-kvartir' => self::CAT_FLAT_RENT,
            'arenda-kvartir/kvartiry-posutochno' => self::CAT_FLAT_RENT_DAY,
            'arenda-kvartir/kvartiry-s-pochasovoy-oplatoy' => self::CAT_FLAT_RENT_DAY,
            'arenda-pomescheniy/arenda-baz-otdyha' => self::CAT_COMMERCIAL_RENT,
            'arenda-pomescheniy/arenda-magazinov-salonov' => self::CAT_COMMERCIAL_RENT,
            'arenda-pomescheniy/arenda-ofisov' => self::CAT_COMMERCIAL_RENT,
            'arenda-pomescheniy/arenda-otdelno-stoyaschih-zdaniy' => self::CAT_COMMERCIAL_RENT,
            'arenda-pomescheniy/arenda-pomescheniy-promyshlennogo-naznacheniya' => self::CAT_COMMERCIAL_RENT,
            'arenda-pomescheniy/arenda-pomescheniy-svobodnogo-naznacheniya' => self::CAT_COMMERCIAL_RENT,
            'arenda-pomescheniy/arenda-restoranov-barov' => self::CAT_COMMERCIAL_RENT,
            'arenda-pomescheniy/arenda-skladov' => self::CAT_COMMERCIAL_RENT,
            'arenda-pomescheniy/prochee' => self::CAT_COMMERCIAL_RENT,
            'arenda-zemli' => self::CAT_LAND_DACHA,
            'ischu-kompanona' => self::CAT_OTHER_OTHER,
            'obmen-nedvizhimosti' => self::CAT_OTHER_OTHER,
            'prochaja-nedvizimost' => self::CAT_OTHER_OTHER,
            'prodazha-domov/prodazha-dach' => self::CAT_HOUSE_SALE,
            'prodazha-domov/prodazha-domov-v-derevne' => self::CAT_HOUSE_SALE,
            'prodazha-domov/prodazha-kottedzhey' => self::CAT_HOUSE_SALE,
            'prodazha-garazhey-stoyanok' => self::CAT_GARAGE_SELL,
            'prodazha-komnat' => self::CAT_ROOM_SALE,
            'prodazha-kvartir/novostroyki' => self::CAT_FLAT_SALE,
            'prodazha-kvartir/vtorichnyy-rynok' => self::CAT_FLAT_SALE,
            'prodazha-pomescheniy/prodazha-baz-otdyha' => self::CAT_COMMERCIAL_RENT,
            'prodazha-pomescheniy/prodazha-magazinov-salonov' => self::CAT_COMMERCIAL_RENT,
            'prodazha-pomescheniy/prodazha-ofisov' => self::CAT_COMMERCIAL_RENT,
            'prodazha-pomescheniy/prodazha-otdelno-stoyaschih-zdaniy' => self::CAT_COMMERCIAL_RENT,
            'prodazha-pomescheniy/prodazha-pomescheniy-promyshlennogo-naznacheniya' => self::CAT_COMMERCIAL_RENT,
            'prodazha-pomescheniy/prodazha-pomescheniy-svobodnogo-naznacheniya' => self::CAT_COMMERCIAL_RENT,
            'prodazha-pomescheniy/prodazha-restoranov-barov' => self::CAT_COMMERCIAL_RENT,
            'prodazha-pomescheniy/prodazha-skladov' => self::CAT_COMMERCIAL_RENT,
            'prodazha-zemli/prodazha-zemli-pod-individualnoe-stroitelstvo' => self::CAT_LAND_DACHA,
            'prodazha-zemli/prodazha-zemli-pod-sad-ogorod' => self::CAT_LAND_DACHA,
            'prodazha-zemli/prodazha-zemli-promyshlennogo-naznacheniya' => self::CAT_LAND_DACHA,
            'prodazha-zemli/prodazha-zemli-selskohozyaystvennogo-naznacheniya' => self::CAT_LAND_DACHA,
        ];

}