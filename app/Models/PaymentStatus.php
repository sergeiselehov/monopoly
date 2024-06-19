<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{

    const CREATED = 10;
    const PROCESSING = 20;
    const WAITING_PAYMENT = 30;
    const MARK_PAID = 40;
    const SUCCESSED = 50;
    const DELETED_BY_MERCHANT = 60;
    const FAILED = 70;
    const IN_DISPUTE = 75;
    const CLOSED_FAILED = 80;
    const REPEATED_CLOSED_FAILED = 90;
    const ACCEPTED_SUCCESSED = 100;
    const REPEATED_ACCEPTED_SUCCESSED = 110;
    const REJECTED_BY_LIMIT_PER_USER = 501;

    protected $guarded = [];

    const NAMES = [
        self::CREATED => [
            'name' => 'CREATED',
            'name_ru' => 'Добавлен',
        ],
        self::PROCESSING => [
            'name' => 'PROCESSING',
            'name_ru' => 'Поиск карты',
        ],
        self::WAITING_PAYMENT => [
            'name' => 'WAITING_PAYMENT',
            'name_ru' => 'Ожидает платежа',
        ],
        self::MARK_PAID => [
            'name' => 'MARK_PAID',
            'name_ru' => 'Отмечен оплаченным',
        ],
        self::SUCCESSED => [
            'name' => 'SUCCESSED',
            'name_ru' => 'Зачислен',
        ],
        self::DELETED_BY_MERCHANT => [
            'name' => 'DELETED_BY_MERCHANT',
            'name_ru' => 'Удален пользователем',
        ],
        self::FAILED => [
            'name' => 'FAILED',
            'name_ru' => 'Отклонен',
        ],
        self::IN_DISPUTE => [
            'name' => 'IN_DISPUTE',
            'name_ru' => 'Открыт спор',
        ],
        self::CLOSED_FAILED => [
            'name' => 'CLOSED_FAILED',
            'name_ru' => 'Закрыто после спора',
        ],
        self::REPEATED_CLOSED_FAILED => [
            'name' => 'REPEATED_CLOSED_FAILED',
            'name_ru' => 'Закрыто повторно после спора',
        ],
        self::ACCEPTED_SUCCESSED => [
            'name' => 'ACCEPTED_SUCCESSED',
            'name_ru' => 'После принятия спора',
        ],
        self::REPEATED_ACCEPTED_SUCCESSED => [
            'name' => 'REPEATED_ACCEPTED_SUCCESSED',
            'name_ru' => 'Повторно после принятия спора',
        ],
        self::REJECTED_BY_LIMIT_PER_USER => [
            'name' => 'REJECTED_BY_LIMIT_PER_USER',
            'name_ru' => 'Отказ в выдаче из-за количества активных заявок',
        ],
    ];

}
