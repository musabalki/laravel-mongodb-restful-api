<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable =["products"];
    protected $hidden = ['updated_at',"_id"];
}
