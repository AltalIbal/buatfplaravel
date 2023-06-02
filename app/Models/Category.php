<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vote() {
        return $this->hasMany(Vote::class,'category_id','id')->orderBy('vote','DESC');
    }
}
