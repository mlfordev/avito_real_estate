<?php

namespace Modules\Main\Controllers;

use JsonException;
use Phact\Controller\Controller;

/**
 * Class BaseController
 * @package Modules\Main\Controllers
 */
class BaseController extends Controller
{
    /**
     * @param array|null $data
     * @param int $code
     * @throws JsonException
     */
    public function jsonResponse(?array $data = [], int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        if (!is_null($data)) {
            echo json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param string $message
     * @param int $code
     */
    public function errorResponse(string $message = 'Ресурс не найден', int $code = 404): void
    {
        http_response_code($code);
        header('Content-Type: text/plain; charset=utf-8');
        echo $message . PHP_EOL;
    }
}