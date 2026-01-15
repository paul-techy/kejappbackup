<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_id',
        'name',
        'bedroom',
        'property_id',
        'baths',
        'kitchen',
        'rent',
        'deposit_amount',
        'deposit_type',
        'late_fee_type',
        'late_fee_amount',
        'incident_receipt_amount',
        'rent_type',
        'rent_duration',
        'start_date',
        'end_date',
        'payment_due_date',
        'is_occupied',
        'parent_id',
        'notes',
    ];

    public static $Types=[
        'fixed'=> 'Fixed',
        'percentage'=>'Percentage',
    ];
    public static $rentTypes=[
         'monthly'=> 'Monthly',
        'yearly'=>'Yearly',
        'custom'=>'Custom',
    ];
    public function properties()
    {
        return $this->hasOne('App\Models\Property','id','property_id');
    }

    public function tenants()
    {
        return Tenant::where('unit',$this->id)->first();
    }

    /**
     * Generate a unique 6-digit unit_id
     */
    public static function generateUnitId()
    {
        do {
            $unitId = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('unit_id', $unitId)->exists());

        return $unitId;
    }

    /**
     * Boot method to auto-generate unit_id when creating a new unit
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($unit) {
            if (empty($unit->unit_id)) {
                $unit->unit_id = self::generateUnitId();
            }
        });
    }

}
