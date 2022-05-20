<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    use HasFactory;
    protected $fillable = ['csv_filename', 'csv_header', 'csv_data' ];
}
