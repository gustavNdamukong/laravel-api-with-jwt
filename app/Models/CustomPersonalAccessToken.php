<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class CustomPersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $table = 'personal_access_tokens';


    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'refresh_token',
    ];
}
