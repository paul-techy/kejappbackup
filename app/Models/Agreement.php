<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'property_id',
        'unit_id',
        'date',
        'terms_condition',
        'description',
        'status',
        'attachment',
        'parent_id',
    ];


    public static $status = [
        'Draft' => 'Draft',
        'Pending' => 'Pending',
        'Completed' => 'Completed',
        'Active' => 'Active',
        'Cancelled' => 'Cancelled',
        'Confirmed' => 'Confirmed',

    ];

    public function properties()
    {
        return $this->hasOne(Property::class, 'id', 'property_id');
    }

    public function units()
    {
        return $this->hasOne(PropertyUnit::class, 'id', 'unit_id');
    }
}
