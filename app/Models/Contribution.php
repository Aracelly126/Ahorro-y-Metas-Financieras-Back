<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $primaryKey = 'contribution_id';

    protected $fillable = [
        'amount',
        'remaining_amount',
        'goal_id'
    ];

    protected $casts = [
        'contribution_date' => 'datetime'
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }
}
