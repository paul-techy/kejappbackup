<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'title',
        'package_amount',
        'is_trial',
        'interval',
        'user_limit',
        'property_limit',
        'tenant_limit',
        'enabled_logged_history',
    ];

    protected $casts = [
        'is_trial' => 'boolean',
    ];

    public static $intervals = [
        'Trial' => 'Trial',
        'Monthly' => 'Monthly',
        'Quarterly' => 'Quarterly',
        'Yearly' => 'Yearly',
        'Unlimited' => 'Unlimited',
    ];

    public function couponCheck()
    {
       $packages= Coupon::whereRaw("find_in_set($this->id,applicable_packages)")->count();
      return $packages;
    }

}
