<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const CODE_SUCCESS = 0;
    const CODE_ERROR = 1;

    /**
     * Build a consistent response payload
     *
     * @param integer $code
     * @param $body any variable which can be converted to JSON
     * @param string $message
     * @return array
     */
    protected function buildResponse(int $code, $body = [], string $message = ''): array
    {
        return [
            'code' => $code,
            'body' => $body,
            'message' => $message,
        ];
    }
}
