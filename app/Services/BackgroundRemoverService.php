<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class BackgroundRemoverService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('REMOVE_BG_API_KEY'); 
    }

    public function removeBackground($imagePath,$outputPath,$outputFileName)
    {
       
        try {
            $response = $this->client->post('https://api.remove.bg/v1.0/removebg', [
                'headers' => [
                    'X-Api-Key' => $this->apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'image_file',
                        'contents' => fopen($imagePath, 'r'),
                    ],
                    [
                        'name' => 'size',
                        'contents' => 'auto',
                    ],
                ],
            ]);

           
         
            $outputPath = storage_path('app/public/' . $outputFileName);

         
            file_put_contents($outputPath, $response->getBody());

            return $outputFileName; 
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function removeBackgroundProfile($imagePath,$outputPath,$outputFileName)
    {
       
        try {
            $response = $this->client->post('https://api.remove.bg/v1.0/removebg', [
                'headers' => [
                    'X-Api-Key' => $this->apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'image_file',
                        'contents' => fopen($imagePath, 'r'),
                    ],
                    [
                        'name' => 'size',
                        'contents' => 'auto',
                    ],
                ],
            ]);

           
         
            $outputPath = storage_path('app/public/profile/' . $outputFileName);

         
            file_put_contents($outputPath, $response->getBody());

            return $outputFileName; 
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function removeBackgroundSignature($imagePath,$outputPath,$outputFileName)
    {
       
        try {
            $response = $this->client->post('https://api.remove.bg/v1.0/removebg', [
                'headers' => [
                    'X-Api-Key' => $this->apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'image_file',
                        'contents' => fopen($imagePath, 'r'),
                    ],
                    [
                        'name' => 'size',
                        'contents' => 'auto',
                    ],
                ],
            ]);

           
         
            $outputPath = storage_path('app/public/signature/' . $outputFileName);

         
            file_put_contents($outputPath, $response->getBody());

            return $outputFileName; 
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
