<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAssistance extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'category',
        'amount',
        'provider',
        'description',
        'is_available',
    ];

    public function socialAssistanceRecipients()
    {
        return $this->belongsTo(SocialAssistanceRecipient::class);
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
