<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class StoreFileInLocal
{
    /**
     * Upload a file to the public folder (cpanel compatible).
     *
     * @param UploadedFile $file
     * @param string $path e.g., 'uploads/images'
     */
    public static function localUploadSingle(UploadedFile $file, string $path): ?string
    {
        try {
            $destinationPath = public_path($path);

            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            $fullPath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;

            $file->move($destinationPath, $fileName);

            $url = asset("$path/$fileName");

            return $url;
        } catch (\Exception $e) {
            Log::error("Error uploading file: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a local file using the full absolute path.
     *
     * @param string $fullPath
     * @return bool
     */
  public static function deleteLocalFile(string $url): bool
{
    try {
        $parsedUrlPath = parse_url($url, PHP_URL_PATH); // e.g., /images/home_banners/file.jpg

        // Handle cPanel or local env properly
        // Remove the first slash to avoid double slash in public_path
        $relativePath = ltrim($parsedUrlPath, '/');

        $fullPath = public_path($relativePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return true;
        }

        Log::warning("File not found for deletion: $fullPath");
        return false;
    } catch (\Exception $e) {
        Log::error("Error deleting file: " . $e->getMessage());
        return false;
    }
}

}
