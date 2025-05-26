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
     * 自訂格式化輸出日期
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * BelongsTo relationship with Client model.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
