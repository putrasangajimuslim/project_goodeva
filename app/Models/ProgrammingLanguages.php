<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgrammingLanguages extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'programming_languages';

    protected $fillable = [
        'name',
    ];
}
