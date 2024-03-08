<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadContactModel extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'lead_contact';
    protected $guarded = [];
}
