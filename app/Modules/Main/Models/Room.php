<?php

namespace Modules\Main\Models;

use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\DateTimeField;
use Phact\Orm\Fields\HasManyField;
use Phact\Orm\Fields\PositionField;
use Phact\Orm\Fields\TextField;
use Phact\Orm\Model;

/**
 * Class Room
 * @package Modules\Main\Models
 */
class Room extends Model
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'main_rooms';
    }

    /**
     * @return array[]
     */
    public static function getFields(): array
    {
        return [
            'description' => [
                'class' => TextField::class,
                'label' => 'Описание',
            ],
            'price' => [
                'class' => CharField::class,
                'label' => 'Цена за ночь',
            ],
            'bookings' => [
                'class' => HasManyField::class,
                'modelClass' => Booking::class,
                'label' => 'Брони',
                'editable' => false
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'editable' => false,
                'label' => 'Дата добавления',
            ],
            'updated_at' => [
                'class' => DateTimeField::class,
                'autoNow' => true,
                'editable' => false,
                'label' => 'Дата изменения',
                'null' => true,
            ],
            'position' => [
                'class' => PositionField::class,
                'editable' => false,
                'relations' => [],
            ],
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->description;
    }
}