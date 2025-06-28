<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Data\UploadingFileData;
use App\Models\TemporaryUploads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait MediaExtraInteractions
{
    /**
     * @param Model $model
     * @param UploadingFileData $data
     * @return void
     */
    public function cleanupTemporaryFile(Model $model, UploadingFileData $data): void
    {
        // Find the temporary upload record
        $temporaryUpload = TemporaryUploads::where([
            'user_id' => $data->user_id,
            'file_name' => $data->file_name,
            'path' => $data->path,
        ])->first();

        if ($temporaryUpload && Storage::disk('public')->exists($temporaryUpload->path)) {
            // Add the file to the media library
            $model->addMediaFromDisk($temporaryUpload->path, 'public')
                ->usingName($temporaryUpload->file_name)
                ->usingFileName($temporaryUpload->file_name)
                ->toMediaCollection(Str::lower(class_basename($model) . '_attachments') . '_attachments');

            // Remove the temporary file from storage
            Storage::disk('public')->delete($temporaryUpload->path);
            // Delete the directory if empty
            $dir = dirname($temporaryUpload->path);
            if (empty(Storage::disk('public')->files($dir))) {
                Storage::disk('public')->deleteDirectory($dir);
            }
            // Delete the temporary upload record
            $temporaryUpload->delete();
        }
    }
}
