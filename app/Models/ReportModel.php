<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportModel extends Model
{
    use HasFactory;

    protected $table = 'report';
    protected $fillable = [
        'subject',
        'context',
        'status',
        'rating',
    ];

    public function detailReport()
    {
        return $this->hasMany(DetailReport::class, 'report_id');
    }

}
