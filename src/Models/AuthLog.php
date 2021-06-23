<?php

namespace OZiTAG\Tager\Backend\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    protected $table = 'tager_auth_logs';
    
    protected $guarded = [];
}
