<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class TemporaryUploads extends Model
{
    use SerializesModels;

    protected $fillable = [
        'user_id',
        'path',
        'file_name',
    ];
}
