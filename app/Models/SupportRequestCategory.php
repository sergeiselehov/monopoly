<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportRequestCategory extends Model
{
    use HasFactory;

    const INTERFACEERROR = 1;
    const PROGRAMMERROR = 2;
    const REQUESTFORHELP = 3;
    const NAMES = [
        self::INTERFACEERROR=>'Ошибка интерфейса',
        self::PROGRAMMERROR=>'Программная ошибка',
        self::REQUESTFORHELP=>'Запрос в техническую поддержку'
    ];
    protected $guarded = [];

}
