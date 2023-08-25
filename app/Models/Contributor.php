<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'collection_id',
        'amount'
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
