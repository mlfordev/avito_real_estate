<?php

namespace Modules\Main\Controllers;

use Exception;
use JsonException;
use Modules\Main\Forms\RoomForm;
use Modules\Main\Models\Booking;
use Modules\Main\Models\Room;
use Phact\Main\Phact;

/**
 * Class RoomController
 * @package Modules\Main\Controllers
 */
class RoomController extends BaseController
{
    /** @var string[] */
    public const ALLOWED_FOR_SORTING = [
        'price',
        '-price',
        'created_at',
        '-created_at',
    ];

    /**
     * @throws JsonException
     */
    public function index(): void
    {
        $order = $this->request->get->get('order', 'created_at');
        $order = in_array($order, static::ALLOWED_FOR_SORTING, true) ? $order : 'created_at';
        $rooms = Room::objects()->order([$order])->values(['id', 'description', 'price', 'created_at']);
        $this->jsonResponse($rooms);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function create(): void
    {
        $form = new RoomForm();
        $data = $this->request->post->all();

        if ($this->request->post->has($form->getName())) {
            $form->fill($data);
        } else {
            $form->fill([$form->getName() => $data]);
        }

        if ($form->valid) {
            $saved = $form->save();
            if ((bool)$saved) {
                $this->jsonResponse(['room_id' => $saved], 201);
            } else {
                $this->jsonResponse(['errors' => ['Номер отеля не сохранился в базе данных']], 500);
            }
            Phact::app()->end();
        }

        $this->jsonResponse(['errors' => $form->getErrorsMessages()], 422); // Запрос корректно разобран, но содержание запроса не прошло серверную валидацию.
    }

    /**
     * @param $id
     * @throws JsonException
     */
    public function destroy(int $id): void
    {
        $isDeleted = Room::objects()->filter(['id' => $id])->delete();
        // Связанные записи удалятся в базе CASCADE. Здесь удаление в коде для подстраховки
        if ($isDeleted) {
            Booking::objects()->filter(['room_id' => $id])->delete();
        }

        $this->jsonResponse(null, 204); // Без тела ответа
    }
}
