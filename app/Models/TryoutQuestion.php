<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutQuestion extends Model
{
    protected $fillable = [
        'tryout_id','question','image_path','audio_path','video_path',
        'option_1','option_2','option_3','option_4','option_5','answer','order'
    ];

    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }
}
