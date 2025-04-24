<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $primaryKey = 'alert_id';

    protected $fillable = [
        'alert_name',
        'alert_message',
        'goal_id'
    ];

    protected $casts = [
        'alert_date' => 'datetime'
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }
}
