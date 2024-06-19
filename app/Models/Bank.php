<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    const SBER = 1;

    const TINKOFF = 2;
    protected $guarded = [];

    const NAMES = [
        self::SBER => [
            'name' => 'Сбербанк',
        ],
        self::TINKOFF => [
            'name' => 'Тинькоф',
        ],
    ];
}
