<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'fileName', 'fileExtension', 'fileSize','width','height','deleted'
    ];
}
