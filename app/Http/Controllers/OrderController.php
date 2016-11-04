<?php

namespace App\Http\Controllers;

use Chumper\Zipper\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{

    /**
     * @param Request $request
     * @param $uniqueId
     */
    public function order(Request $request)
    {
        $rewrite = $request->get('rewrite');
        $uniqueId = $request->get('uniqueId');
        $filenames = array_filter($request->get('images'));

        $remamedImageCount = $this->renameImages($filenames, $uniqueId, $rewrite);
        $orderedImagesZipFile = $this->createZipFile($uniqueId);

        return response()->json(['success' => true, 'renamed_images_count' => $remamedImageCount, 'zip_url' => $orderedImagesZipFile]);
    }

    /**
     * @param $filenames
     * @param $uniqueId
     * @return int
     */
    private function renameImages($filenames, $uniqueId, $rewrite)
    {
        $count = 1;
        $originalDirectory = public_path('images').'/'.$uniqueId.'/original';
        $orderedDirectory = public_path('images').'/'.$uniqueId.'/ordered';

        foreach ($filenames as $filename) {

            $originalFile = $originalDirectory.'/'.$filename;
            copy($originalFile, $orderedDirectory.'/'.$filename);

            $renamedName = $orderedDirectory.'/'.sprintf("%02d", $count).'_'.$filename;
            if ($rewrite == true) {
                $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
                $renamedName = $orderedDirectory.'/'.sprintf("%02d", $count).'.'.$fileExtension;
            }

            rename($orderedDirectory.'/'.$filename, $renamedName);

            $count++;
        }
        
        return $count;
    }

    /**
     * @param $uniqueId
     * @return string
     */
    private function createZipFile($uniqueId)
    {
        $zipper = new Zipper();

        $zipFilename = 'ordered-images.zip';
        $orderedImagesDirectory = public_path('images').'/'.$uniqueId.'/ordered';
        $orderedImages = glob($orderedImagesDirectory.'/*');
        $zipper->make($orderedImagesDirectory.'/../'.$zipFilename)->add($orderedImages);

        return url('/images').'/'.$uniqueId.'/'.$zipFilename;
    }



}
