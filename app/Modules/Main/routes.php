<?php

return [
    [
        'methods' => ['GET'],
        'route' => '',
        'target' => [\Modules\Main\Controllers\MainController::class, 'index'],
        'name' => 'index'
    ],
    [
        'methods' => ['GET'],
        'route' => '/rooms',
        'target' => [\Modules\Main\Controllers\RoomController::class, 'index'],
        'name' => 'room_index'
    ],
    [
        'methods' => ['POST'],
        'route' => '/rooms',
        'target' => [\Modules\Main\Controllers\RoomController::class, 'create'],
        'name' => 'room_create'
    ],
    [
        'methods' => ['DELETE'],
        'route' => '/rooms/{:id}',
        'target' => [\Modules\Main\Controllers\RoomController::class, 'destroy'],
        'name' => 'room_destroy'
    ],
    [
        'methods' => ['GET'],
        'route' => '/rooms/{:roomId}/bookings',
        'target' => [\Modules\Main\Controllers\BookingController::class, 'index'],
        'name' => 'booking_index'
    ],
    [
        'methods' => ['POST'],
        'route' => '/rooms/{:roomId}/bookings',
        'target' => [\Modules\Main\Controllers\BookingController::class, 'create'],
        'name' => 'booking_create'
    ],
    [
        'methods' => ['DELETE'],
        'route' => '/bookings/{:id}',
        'target' => [\Modules\Main\Controllers\BookingController::class, 'destroy'],
        'name' => 'booking_destroy'
    ],
];