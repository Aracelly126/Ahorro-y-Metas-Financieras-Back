<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'category_description'
    ];

    public function goals()
    {
        return $this->hasMany(Goal::class, 'category_id');
    }
}
