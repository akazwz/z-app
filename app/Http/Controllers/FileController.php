<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function UploadFile(Request $request): JsonResponse
    {
        $file = $request->file('file');

        if (empty($file)) {
            $res = ['code' => 4000, 'msg' => 'FILE NULL',];
            return response()->json($res);
        }

        if (!$file->isValid()) {
            $res = ['code' => 4000, 'msg' => 'FILE INVALID',];
            return response()->json($res);
        }

        $extension = $file->getClientOriginalExtension();

        $fileName = Str::uuid() . '.' . $extension;

        $file->storeAs('public/file', $fileName);

        $data = ['file_name' => $fileName];
        $res = ['code' => 2000, 'msg' => 'SUCCESS', 'data' => $data];
        return response()->json($res);
    }
}
