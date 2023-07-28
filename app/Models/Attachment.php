<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'path'
    ];

    /**
     * An attachment belongs to one tweet.
     */
    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
