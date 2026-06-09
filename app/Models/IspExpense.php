<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IspExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'description',
        'amount',
        'expense_date',
        'reference_no',
        'added_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    /** Human-readable category labels */
    public static array $categories = [
        'item_purchase'   => 'Item Purchase',
        'raw_bill'        => 'Raw Bill',
        'employee_salary' => 'Employee Salary',
        'miscellaneous'   => 'Miscellaneous',
    ];

    /** Category badge colours (Bootstrap) */
    public static array $categoryColors = [
        'item_purchase'   => 'primary',
        'raw_bill'        => 'warning',
        'employee_salary' => 'info',
        'miscellaneous'   => 'secondary',
    ];

    public function getCategoryLabelAttribute(): string
    {
        return self::$categories[$this->category] ?? ucfirst($this->category);
    }

    public function getCategoryColorAttribute(): string
    {
        return self::$categoryColors[$this->category] ?? 'secondary';
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function scopeByMonth($query, int $month, int $year)
    {
        return $query->whereMonth('expense_date', $month)
                     ->whereYear('expense_date', $year);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('expense_date', [$from, $to]);
    }
}
