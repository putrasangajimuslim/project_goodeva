<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProgrammingLanguages;

class Projects extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'image',
        'category',
    ];

    /**
     * Get the Programming Languages that owns the Projects
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function programmingType()
    // {
    //     return $this->belongsTo(ProgrammingLanguages::class, 'programming_language_id');
    // }
}
