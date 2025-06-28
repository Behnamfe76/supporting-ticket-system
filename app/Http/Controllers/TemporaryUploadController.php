<?php

namespace App\Http\Controllers;

use App\Models\TemporaryUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Bus;
use App\Jobs\DeleteTemporaryUploadJob;

class TemporaryUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $tempDir = 'temp/' . Str::random(40);
        $path = $file->storeAs($tempDir, $file->getClientOriginalName(), 'public');

        $tempUpload = TemporaryUploads::create([
            'user_id' => $user->id,
            'path' => $path,
            'file_name' => $file->getClientOriginalName(),
        ]);

        DeleteTemporaryUploadJob::dispatch($tempUpload)->delay(now()->addMinutes(15));

        return response()->json([
            'file_name' => $tempUpload->file_name,
            'path' => $tempUpload->path,
        ]);
    }
}
