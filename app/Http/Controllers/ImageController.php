<?php
namespace App\Http\Controllers;

use App\Services\BackgroundRemoverService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ImageController extends Controller
{
    protected $backgroundRemover;

    public function __construct(BackgroundRemoverService $backgroundRemover)
    {
        $this->backgroundRemover = $backgroundRemover;
    }

    public function removeBackground(Request $request)
    {
        try {
            $request->validate([
                'images'   => 'required|array',
                'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $processedImages = [];

            foreach ($request->file('images') as $image) {

                $imagePath = $image->store('temp', 'local');

                $outputFileName = 'removed_bg_' . time() . '_' . uniqid() . '.png';
                $outputPath     = storage_path('app/public/' . $outputFileName);

                $this->backgroundRemover->removeBackground(storage_path('app/' . $imagePath), $outputPath, $outputFileName);

                $processedImages[] = [
                    'image_url' => asset('storage/' . $outputFileName),
                    'file_name' => $outputFileName,
                ];
            }

            return response()->json($processedImages);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to remove background.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeBackgroundZip(Request $request)
    {
        try {
            $request->validate([
                'images'   => 'required|array',
                'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $processedImages = [];
            $zipFileName     = 'processed_images_' . time() . '.zip';
            $zipFilePath     = storage_path('app/public/' . $zipFileName);

            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                foreach ($request->file('images') as $image) {
                   
                    $imagePath = $image->store('temp', 'local');

                  
                    $outputFileName = 'removed_bg_' . time() . '_' . uniqid() . '.png';
                    $outputPath     = storage_path('app/public/' . $outputFileName);

                   
                    $this->backgroundRemover->removeBackground(storage_path('app/' . $imagePath), $outputPath, $outputFileName);

                   
                    $zip->addFile($outputPath, $outputFileName);

                   
                    $processedImages[] = [
                        'image_url' => asset('storage/' . $outputFileName),
                        'file_name' => $outputFileName,
                    ];
                }

                $zip->close();
            } else {
                return response()->json(['error' => 'Could not create ZIP file'], 500);
            }

            return response()->json([
                'images'        => $processedImages,
                'zip_url'       => asset('storage/' . $zipFileName),
                'zip_file_name' => $zipFileName,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to process images.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
