<?php

namespace Modules\Main\Controllers;

use JsonException;
use Modules\Main\Forms\BookingForm;
use Modules\Main\Models\Booking;
use Modules\Main\Models\Room;
use Phact\Main\Phact;

/**
 * Class BookingController
 * @package Modules\Main\Controllers
 */
class BookingController extends BaseController
{
    /**
     * @param $roomId
     * @throws JsonException
     */
    public function index(int $roomId): void
    {
        /** @var array $bookings */
        $bookings = Booking::objects()
            ->filter(['room_id' => $roomId])
            ->order(['date_start'])
            ->values(['id', 'date_start', 'date_end']);

        $this->jsonResponse($bookings);
    }

    /**
     * @param int $roomId
     * @throws JsonException
     */
    public function create(int $roomId): void
    {
        $form = new BookingForm();
        $data = $this->request->post->all();
        $room = Room::objects()->filter(['id' => $roomId])->limit(1)->get();

        if ($this->request->post->has($form->getName())) {
            $data[$form->getName()]['room'] = $room;
            $form->fill($data);
        } else {
            $data['room'] = $room;
            $form->fill([$form->getName() => $data]);
        }

        if ($form->valid) {
            $saved = $form->save();
            if ((bool)$saved) {
                $this->jsonResponse(['booking_id' => $saved], 201);
            } else {
                $this->jsonResponse(['errors' => ['Бронь не сохранилась в базе данных']], 500);
            }
            Phact::app()->end();
        }

        $this->jsonResponse(['errors' => $form->getErrorsMessages()], 422); // Запрос корректно разобран, но содержание запроса не прошло серверную валидацию.
    }

    /**
     * @param int $id
     * @throws JsonException
     */
    public function destroy(int $id): void
    {
        Booking::objects()->filter(['id' => $id])->delete();
        $this->jsonResponse(null, 204); // Без тела ответа
    }
}