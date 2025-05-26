<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * Guarded attributes.
     */
    protected $guarded = [
        'id'
    ];

    /**
     * HasMany relationship with Contact model.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * BelongsTo relationship with User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
