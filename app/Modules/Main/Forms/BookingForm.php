<?php

namespace Modules\Main\Forms;


use Closure;
use Modules\Main\Models\Booking;
use Phact\Form\Fields\CharField;
use Phact\Form\ModelForm;

class BookingForm extends ModelForm
{
    /**
     * @var string[]
     */
    public $exclude = ['created_at', 'updated_at', 'position'];

    /**
     * @return array[]
     */
    public function getFields(): array
    {
        return [
            'room' => [
                'class' => CharField::class,
                'label' => 'ID номера',
                'required' => true,
                'requiredMessage' => 'Номер отеля с таким ID не существует',
            ],
            'date_start' => [
                'class' => CharField::class,
                'label' => 'Дата начала брони',
                'required' => true,
                'validators' => [static::getDateValidator()],
            ],
            'date_end' => [
                'class' => CharField::class,
                'label' => 'Дата окончания брони',
                'required' => true,
                'validators' => [static::getDateValidator()],
            ],
        ];
    }

    /**
     * @return Booking
     */
    public function getModel(): Booking
    {
        return new Booking();
    }

    /**
     * @return Closure
     */
    public static function getDateValidator(): Closure
    {
        return static function ($value) {
            if (!empty($value)) {
                $dateArray = explode('-', $value);
                if (
                    checkdate($dateArray[1], $dateArray[2], $dateArray[0])
                    && strtotime($value) > time()
                ) {
                    return true;
                }
            }
            return 'Должно быть будущей датой в формате ГГГГ-ММ-ДД';
        };
    }

    /**
     * @param $attributes
     */
    public function clean($attributes): void
    {
        $timeStart = strtotime($attributes['date_start']);
        $timeEnd = strtotime($attributes['date_end']);

        if ($timeEnd < $timeStart) {
            $this->addError('date_end', 'Дата окончания брони должна быть больше или равна даты начала брони');
        }
    }

}
