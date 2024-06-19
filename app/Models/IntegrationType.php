<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationType extends Model
{
    protected $guarded = [];



    const  FLEXO = 1;
    const PAYMENTSGATE = 2;

    const HOSTTOHOST = 3;

    const NAMES = [
        self::FLEXO => [
            'name' => 'Flexo',
            'type' => 'flexo',
            'base_url'=>'https://flexo-p2p.pro/index.html?id='
        ],
        self::PAYMENTSGATE => [
            'name' => 'PaymentsGate',
            'type' => 'paymentsgate',
            'base_url'=>'https://p2p.paymentsgate.pro/index.html?id='
        ],
        self::HOSTTOHOST =>[
            'name' => 'HOSTTOHOST',
            'type' => 'HOSTTOHOST',
            'base_url'=>null
        ]
    ];
}
