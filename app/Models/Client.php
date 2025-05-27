<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * Guarded attributes.
     */
    protected $guarded = [
        'id'
    ];

    /**
     * 自訂格式化輸出日期
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

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
