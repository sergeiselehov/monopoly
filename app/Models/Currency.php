<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{

    const RUB = 1;
    const UZS = 2;


    protected $guarded = [];

    const NAMES = [
        self::RUB => [
            'name' => 'RUB',
            'name_ru' => 'Рубль',
        ],
        self::UZS => [
            'name' => 'UZS',
            'name_ru' => 'Суммы',
        ],
    ];
}
