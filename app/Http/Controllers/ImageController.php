<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class ImageController
 * @package App\Http\Controllers
 */
class ImageController extends Controller
{

    /**
     * @param $uniqueId
     * @return \Illuminate\Http\JsonResponse
     */
    public function listing($uniqueId)
    {
        $directory = public_path('images').'/'.$uniqueId.'/original';
        $files = array_diff(scandir($directory), array('.', '..'));
        foreach ($files as $key => $file) {

            $fileDetails = [];
            $fileDetails['path'] = url('/images').'/'.$uniqueId.'/original/'.$file;
            $fileDetails['name'] = $file;

            $files[$key] = $fileDetails;
        }

        return response()->json($files);
    }

}
