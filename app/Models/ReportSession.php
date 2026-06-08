<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSession extends Model
{
    use HasFactory;

    protected $table = 'report_sessions';

    protected $fillable = [
        'operator_id',
        'session_start',
        'session_end',
        'state',
    ];

    protected $dates = [
        'session_start',
        'session_end',
        'created_at',
        'updated_at',
    ];

    /**
     * Relation to the operator (User model).
     */
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
?>
