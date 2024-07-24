<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'action',
        'user',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
