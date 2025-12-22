<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Category;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'detail',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeKeyword(Builder $query, ?string $keyword, string $match = 'partial'): Builder
    {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        $match = $match === 'exact' ? 'exact' : 'partial';
        $op = $match === 'exact' ?  '=' : 'like';
        $val = $match === 'exact' ? $keyword : "%{$keyword}%";

        $normalized = str_replace('　', ' ', $keyword);
        $parts = array_values(array_filter(explode(' ', $normalized)));

        return $query->where(function ($q) use ($op, $val, $match, $normalized, $parts) {

            $q->orWhere('email', $op, $val);

            $q->orWhere('last_name', $op, $val)->orWhere('first_name', $op, $val);

            if($match === 'exact') {
                $q->orWhereRaw(
                    'CONCAT(last_name, first_name) = ?',
                    [str_replace(' ', '', $normalized)]
                );
            } else {
                $q->orWhereRaw(
                   'CONCAT(last_name, first_name) LIKE ?',
                    ['%' . str_replace(' ', '', $normalized) . '%']
                );
            }

            if (count($parts) >= 2) {
                [$last, $first] = $parts;

                if ($match === 'exact') {
                    $q->orWhere(function ($qq) use ($last, $first) {
                        $qq->where('last_name', $last)->where('first_name', $first);
                    });
                } else {
                    $q->orWhere(function ($qq) use ($last, $first) {
                        $qq->where('last_name', 'like', "%{$last}%")->where('first_name', 'like', "%{$first}%");
                    });
                }
            }
        });
    }

    public function scopeGender(Builder $query, ?string $gender): Builder
    {
        if ($gender === null || $gender === '' || $gender === 'all') {
            return $query;
        }

        return $query->where('gender', $gender);
    }

    public function scopeCategory(Builder $query, ?string $categoryId): Builder
    {
        if ($categoryId === null || $categoryId === '' || $categoryId === 'all') {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    public function scopeCreatedDate(Builder $query, ?string $date): Builder
    {
        if ($date === null || $date === '') {
            return $query;
        }

        return $query->whereDate('created_at', $date);
    }
}
