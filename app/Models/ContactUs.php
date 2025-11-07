<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends MyModel
{
    use HasFactory;
    protected $table = 'contact_us';
    protected $revisionCleanup = true;
    protected $historyLimit = 500;
    protected $guarded = [];
}
