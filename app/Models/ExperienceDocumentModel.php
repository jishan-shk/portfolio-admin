<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienceDocumentModel extends Model
{
    use HasFactory;

    protected $table = 'experience_documents';
    protected $guarded = [];
}
