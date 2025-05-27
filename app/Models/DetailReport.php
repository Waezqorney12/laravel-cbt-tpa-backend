<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReport extends Model
{
    use HasFactory;

    protected $table = 'report_detail';
    protected $fillable = [
        'user_id',
        'report_id',
    ];

    public function report()
    {
        return $this->belongsTo(ReportModel::class, 'report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
