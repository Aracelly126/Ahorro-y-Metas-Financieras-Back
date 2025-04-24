<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $primaryKey = 'goal_id';

    protected $fillable = [
        'goal_name',
        'target_amount',
        'deadline_date',
        'category_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Y esta otra relación con categorías
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'goal_id');
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class, 'goal_id');
    }
}
