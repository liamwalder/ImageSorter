<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class UploadController
 * @package App\Http\Controllers
 */
class UploadController extends Controller
{

    /**
     * @param Request $request
     */
    public function upload(Request $request)
    {
        $uniqueId = uniqid();

        $validator = $this->validation($request);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 400);
        }

        $uniqueImageDirectory = $this->createUniqueIdImageDirectory($uniqueId);
        $originalFileDirectory = $this->createOriginalFileDirectory($uniqueImageDirectory);

        $this->moveImages($request->all()['images'], $originalFileDirectory);

        return response()->json(['unique_id' => $uniqueId]);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function validation($request)
    {
        return Validator::make($request->all(), ['images.*' => 'image'], ['images.*' => 'All images must be of the type jpeg, png, bmp, gif, or svg.',]);
    }

    /**
     * @param $images
     * @param $originalFileDirectory
     */
    private function moveImages($images, $originalFileDirectory)
    {
        foreach ($images as $image) {
            $imageName = $image->getClientOriginalName();
            $image->move($originalFileDirectory, $imageName);
        }
    }

    /**
     * @param $uniqueId
     * @return string
     */
    private function createUniqueIdImageDirectory($uniqueId)
    {
        $uniqueFileDirectory = public_path('images').'/'.$uniqueId;
        if (!file_exists($uniqueFileDirectory)) {
            mkdir($uniqueFileDirectory);
        }
        return $uniqueFileDirectory;
    }

    /**
     * @param $uniqueImageDirectory
     * @return string
     */
    private function createOriginalFileDirectory($uniqueImageDirectory)
    {
        $originalFileDirectory = $uniqueImageDirectory.'/original';
        if (!file_exists($originalFileDirectory)) {
            mkdir($originalFileDirectory);
        }

        $this->createOrderedFileDirectory($uniqueImageDirectory);

        return $originalFileDirectory;
    }

    /**
     * @param $uniqueImageDirectory
     */
    private function createOrderedFileDirectory($uniqueImageDirectory)
    {
        $orderedFileDirectory = $uniqueImageDirectory.'/ordered';
        if (!file_exists($orderedFileDirectory)) {
            mkdir($orderedFileDirectory);
        }
    }

}
