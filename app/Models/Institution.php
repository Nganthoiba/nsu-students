<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    protected $fillable = ['institution_name', 'institution_code', 'institution_email'];
    use SoftDeletes;
    protected $connection = 'mongodb';
    protected $collection = 'institutions';
}
