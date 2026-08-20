<?php

namespace App\Services;

use Aws\S3\S3Client;
use GuzzleHttp\Client;
use Storage;
use Illuminate\Support\Str;

class UploadService
{
    /**
     * Storage image
     * @return string
     */
    // public function uploadFile($file, $imagePath)
    // {
    //     $name = "ms_remit_" . Str::random(6) .  "_". microtime(true) . ".". $file->extension();
        
    //     $imagePath = config('filesystems.disks.s3.s3_prefix') . $imagePath;

    //     return $file->storeAs($imagePath, $name, 's3');
    // }

    /**
     * Delete image
     */
    public function deleteFile($imagePath)
    {
        if ($imagePath != null) {
            \Storage::disk('s3')->delete($imagePath);
        }
    }

    /**
     * Generate presigned url for s3 upload
     */
    public function generatePresignedUrl($file, $path)
    {
        $expiryMinutes = 1;
        
        $fileName = $file->getClientOriginalName();

        $fileType = $file->getMimeType();

        $generatedName = "ms_remit_" . Str::random(6) . "_" . time() . "." . pathinfo($fileName, PATHINFO_EXTENSION);
        $prefix = config('filesystems.disks.s3.s3_prefix', '');
        $key = $prefix . '/' . $path . '/' . $generatedName;

        $s3Client = new S3Client([
            'version'     => 'latest',
            'region'      => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);

        $cmd = $s3Client->getCommand('PutObject', [
            'Bucket'      => config('filesystems.disks.s3.bucket'),
            'Key'         => $key,
            'ContentType' => $fileType,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+' . $expiryMinutes . ' minutes');

        return [
            'upload_url' => (string) $request->getUri(),
            'file_path'  => $key,
            'expires_in' => $expiryMinutes * 60,
        ];
    }

    /**
     * Upload file to S3 using presigned URL
     */
    public function uploadPresignedFile($file, $presignedUrl)
    {
        $client = new Client();

        $fileType = $file->getMimeType() ?? 'application/octet-stream';

        $client->put($presignedUrl, [
            'body' => fopen($file->getRealPath(), 'r'),
            'headers' => [
                'Content-Type' => $fileType,
            ],
        ]);

        return true;
    }
}