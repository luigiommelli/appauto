<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_model',
        'license_plate',
        'chassis',
        'registration_year',
        'km', 
        'color',
        'fuel_type',
        'second_key',
        'origin',
        'vat_exposed',
        'purchase_date',
        'registry_number',
        'archive_number',
        'purchase_price',
        'broker',
        'transport',
        'mechatronics',
        'bodywork',
        'tire_shop',
        'upholstery',
        'travel',
        'inspection',
        'miscellaneous',
        'spare_parts',
        'washing',
        'total_cost',
        'customer_name',
        'customer_surname',
        'payment_method',
        'phone_number',
        'payment_details',
        'status',        // ← Sostituisce sold e archived
        'sale_price',
    ];

    protected $casts = [
        'second_key' => 'boolean',
        'vat_exposed' => 'boolean',
        'purchase_date' => 'date',
        'payment_details' => 'array',
        'status' => 'string',    // ← Manteniamo solo questo
    ];
    
    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }
}