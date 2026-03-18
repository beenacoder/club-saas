<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected $fillable = [
    'nombre',
    'email',
];
}
