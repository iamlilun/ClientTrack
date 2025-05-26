<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * Guarded attributes.
     */
    protected $guarded = [
        'id'
    ];

    /**
     * BelongsTo relationship with Client model.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
