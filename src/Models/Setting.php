<?php

namespace LWSoftBD\LwSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'lw_settings';
    protected $fillable = ['group', 'key', 'value', 'type'];
}
