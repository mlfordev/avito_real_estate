<?php

namespace Modules\Main\Forms;


use Modules\Main\Models\Room;
use Phact\Form\Fields\CharField;
use Phact\Form\Fields\TextAreaField;
use Phact\Form\ModelForm;

/**
 * Class RoomForm
 * @package Modules\Main\Forms
 */
class RoomForm extends ModelForm
{
    /**
     * @var string[]
     */
    public $exclude = ['bookings', 'created_at', 'updated_at', 'position'];

    /**
     * @return array[]
     */
    public function getFields(): array
    {
        return [
            'description' => [
                'class' => TextAreaField::class,
                'label' => 'Описание',
                'required' => true
            ],
            'price' => [
                'class' => CharField::class,
                'label' => 'Цена',
                'required' => true,
                'validators' => [
                    static function ($value) {
                        if (filter_var($value, FILTER_VALIDATE_FLOAT) && (int)$value > 0) {
                            return true;
                        }
                        return "Должно быть положительным числом. Разделитель целой и дробной части - точка";
                    },
                ],
            ],
        ];
    }

    /**
     * @return Room
     */
    public function getModel(): Room
    {
        return new Room();
    }
}
