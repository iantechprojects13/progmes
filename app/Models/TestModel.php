<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasFactory;

    protected $table = 'test_table';

    protected $fillable = [
        'test_column_1',
        'test_column_2',
        'test_column_3',
        'test_column_4',
    ];
}
