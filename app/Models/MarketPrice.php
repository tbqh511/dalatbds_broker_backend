<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $fillable = [
        'area_name', 'ward_code', 'avg_price_m2', 'prev_price_m2', 'month', 'year',
    ];

    protected $casts = [
        'avg_price_m2' => 'float',
        'prev_price_m2' => 'float',
    ];

    /**
     * Scope: lấy bản ghi của tháng gần nhất có dữ liệu.
     */
    public function scopeLatestMonth($query)
    {
        $latest = static::selectRaw('month, year')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->first();

        if (!$latest) {
            return $query->whereRaw('1=0');
        }

        return $query->where('month', $latest->month)->where('year', $latest->year);
    }

    /**
     * Giá hiển thị kiểu "28.5tr".
     */
    public function getFormattedPriceAttribute(): string
    {
        $val = $this->avg_price_m2 / 1_000_000;
        return rtrim(rtrim(number_format($val, 1), '0'), '.') . 'tr';
    }

    /**
     * Phần trăm thay đổi so tháng trước (1 chữ số thập phân).
     */
    public function getTrendPctAttribute(): string
    {
        if (!$this->prev_price_m2 || $this->prev_price_m2 == 0) {
            return '0.0';
        }
        $pct = (($this->avg_price_m2 - $this->prev_price_m2) / $this->prev_price_m2) * 100;
        return number_format(abs($pct), 1);
    }

    /**
     * Chiều xu hướng: 'up', 'dn', 'flat'.
     */
    public function getTrendDirAttribute(): string
    {
        if (!$this->prev_price_m2 || $this->prev_price_m2 == 0) {
            return 'flat';
        }
        $diff = $this->avg_price_m2 - $this->prev_price_m2;
        if ($diff > 0) return 'up';
        if ($diff < 0) return 'dn';
        return 'flat';
    }
}
