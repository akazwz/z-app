<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function unauthorized($code, $msg, $data): JsonResponse
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return response()->json($res, Response::HTTP_UNAUTHORIZED);
    }

    public function commonSuccess($code, $msg, $data = null): JsonResponse
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        if (is_null($data)) {
            $res = [
                'code' => $code,
                'msg' => $msg,
            ];
        }
        return response()->json($res, Response::HTTP_OK);
    }

    public function createSuccess($code, $msg, $data): JsonResponse
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return response()->json($res, Response::HTTP_CREATED);
    }

    public function commonFailed($code, $msg, $data): JsonResponse
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return response()->json($res, Response::HTTP_BAD_REQUEST);
    }
}
