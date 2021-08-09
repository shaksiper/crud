<?php

namespace Tables;

use Helpers\Table;

class Users extends Table
{
    protected $fields = [
        'name' => ['string:6'],
        'email' => ['unique:Users'],
        'created_at' => ['default:current_timestamp'],
        'updated_at' => ['on_update:current_timestamp'],
    ];

    protected $fillable = ['name', 'email'];
}
