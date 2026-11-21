<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenyediaProfile extends Model
{
    protected $table = 'penyedia_profiles';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lowongan(): HasMany
    {
        return $this->hasMany(Lowongan::class, 'penyedia_id');
    }
}