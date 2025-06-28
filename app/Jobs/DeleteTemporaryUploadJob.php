<?php

namespace App\Jobs;

use App\Models\TemporaryUploads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteTemporaryUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public TemporaryUploads $tempUpload;

    public function __construct(TemporaryUploads $tempUpload)
    {
        $this->tempUpload = $tempUpload;
        $this->onQueue('deleteTempUploads');
    }

    public function handle(): void
    {
        $path = $this->tempUpload->path;
        // Delete the file
        Storage::disk('public')->delete($path);
        // Delete the directory if empty
        $dir = dirname($path);
        if (empty(Storage::disk('public')->files($dir))) {
            Storage::disk('public')->deleteDirectory($dir);
        }
        // Delete the database record
        $this->tempUpload->delete();
    }
}
