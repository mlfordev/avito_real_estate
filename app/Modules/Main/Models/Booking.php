<?php

namespace Modules\Main\Models;

use Phact\Orm\Fields\DateField;
use Phact\Orm\Fields\DateTimeField;
use Phact\Orm\Fields\ForeignField;
use Phact\Orm\Fields\PositionField;
use Phact\Orm\Model;

/**
 * Class Booking
 * @package Modules\Main\Models
 */
class Booking extends Model
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'main_bookings';
    }

    /**
     * @return array[]
     */
    public static function getFields(): array
    {
        return [
            'room' => [
                'class' => ForeignField::class,
                'modelClass' => Room::class,
                'label' => 'Номер отеля',
            ],
            'date_start' => [
                'class' => DateField::class,
                'editable' => false,
                'label' => 'Дата начала брони',
            ],
            'date_end' => [
                'class' => DateField::class,
                'editable' => false,
                'label' => 'Дата окончания брони',
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
        return $this->room_id . ': ' . $this->date_start . '-' . $this->date_end;
    }
}