<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    use HasFactory;
    protected $table = 'draft';

    public function eldwry() {
        return $this->belongsTo(\App\Models\Eldwry::class, 'eldwry_id');
    }

    public function sub_eldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class, 'sub_eldwry_id');
    }

    public function teams() {
        return $this->belongsTo(\App\Models\Team::class, 'fav_team', 'id');
    }

    public function type() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id', 'id');
    }

}
