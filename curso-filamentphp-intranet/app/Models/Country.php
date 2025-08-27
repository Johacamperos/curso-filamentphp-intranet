<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Country extends Model
{
    protected $table = 'countries';
    public $timestamps = false;

    protected $guarded = []; 
    protected $casts = [
        'status' => 'integer',
    ];

    public function states() { return $this->hasMany(State::class); }
    public function cities()  { return $this->hasMany(City::class); }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

}

