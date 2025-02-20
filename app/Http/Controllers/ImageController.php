<?php
namespace App\Http\Controllers;

use App\Services\BackgroundRemoverService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

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

    public function removeBackgroundProfile(Request $request)
    {
        try {
        
            $validator = Validator::make($request->all(), [
                'image_url' => 'required|url',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error'   => 'Validation failed.',
                    'message' => $validator->errors(),
                ], 422);
            }
    
            $imageUrl = $request->input('image_url');
            $fileName = basename($imageUrl);
    
         
            $response = Http::get($imageUrl);
            if ($response->failed()) {
                return response()->json(['error' => 'Failed to download image.'], 400);
            }
    
            $imageContents = $response->body();
            if (!$imageContents) {
                return response()->json(['error' => 'Downloaded image is empty.'], 400);
            }
    
          
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($imageContents);
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    
            if (!in_array($mimeType, $allowedMimeTypes)) {
                return response()->json(['error' => 'Invalid image format. Only JPG, PNG, and WebP are allowed.'], 400);
            }
    
         
            $extension  = explode('/', $mimeType)[1]; 
            $imageName  = 'downloaded_' . time() . '.' . $extension;
            $imagePath  = 'temp/' . $imageName;
    
            Storage::disk('local')->put($imagePath, $imageContents);
    
          
            $outputFileName =  $fileName;
            $outputPath     = storage_path('app/profile/' . $outputFileName);
    
           
            $this->backgroundRemover->removeBackgroundProfile(storage_path('app/' . $imagePath), $outputPath, $outputFileName);
    
           
            
            if (Storage::disk('local')->exists($imagePath)) {
                Storage::disk('local')->delete($imagePath);
            }


            return response()->json([
                'image_url' => asset('storage/profile/' . $outputFileName),
                'file_name' => $outputFileName,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to remove background.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeBackgroundSignature(Request $request)
    {
        try {
        
            $validator = Validator::make($request->all(), [
                'image_url' => 'required|url',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error'   => 'Validation failed.',
                    'message' => $validator->errors(),
                ], 422);
            }
    
            $imageUrl = $request->input('image_url');

            $fileName = basename($imageUrl);

           
    
         
            $response = Http::get($imageUrl);
            if ($response->failed()) {
                return response()->json(['error' => 'Failed to download image.'], 400);
            }
    
            $imageContents = $response->body();
            if (!$imageContents) {
                return response()->json(['error' => 'Downloaded image is empty.'], 400);
            }
    
          
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($imageContents);
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

           
    
            if (!in_array($mimeType, $allowedMimeTypes)) {
                return response()->json(['error' => 'Invalid image format. Only JPG, PNG, and WebP are allowed.'], 400);
            }
    
         
            $extension  = explode('/', $mimeType)[1]; 
            $imageName  = 'downloaded_' . time() . '.' . $extension;
            $imagePath  = 'temp/' . $imageName;
    
            Storage::disk('local')->put($imagePath, $imageContents);
    
          
            $outputFileName = $fileName;
            $outputPath     = storage_path('app/public/signature/' . $outputFileName);
    
           
            $this->backgroundRemover->removeBackgroundSignature(storage_path('app/' . $imagePath), $outputPath, $outputFileName);
    
            if (Storage::disk('local')->exists($imagePath)) {
                Storage::disk('local')->delete($imagePath);
            }
           
            return response()->json([
                'image_url' => asset('storage/signature/' . $outputFileName),
                'file_name' => $outputFileName,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to remove background.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
