<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneBookItem extends Model
{
    use HasFactory, SoftDeletes;

    public function numbers()
    {
        return $this->hasMany(PhoneBookItemNumber::class, 'phone_book_item_id');
    }
}
