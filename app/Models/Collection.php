<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'link',
    ];

    public function contributors()
    {
        return $this->hasMany(Contributor::class);
    }
}
