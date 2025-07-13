<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address',
    ];

    // Add status constants for consistency
    const STATUS_DIPROSES = 'diproses';
    const STATUS_SELESAI = 'terpenuhi';
    const STATUS_BATAL = 'batal';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
     public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Helper method to get formatted status
    public function getFormattedStatusAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'diproses' => 'Diproses',
            'terpenuhi' => 'Terpenuhi',
            'batal' => 'Batal',
            default => ucfirst($this->status)
        };
    }
}
