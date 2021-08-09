<?php

namespace Tables;

use Helpers\Table;

class UserPosts extends Table
{
    protected $fields = [
        'name' => ['string:64'],
        'email' => ['unique:Users'],
        'created_at' => ['default:current_timestamp'],
        'updated_at' => ['default:current_timestamp', 'on_update:current_timestamp'],
    ];

    protected $fillable = ['name', 'email'];
}
