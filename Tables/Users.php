<?php

namespace Tables;

use Helpers\Table;

class Users extends Table
{
    protected $fields = [
        'name' => ['string:64'],
        'email' => ['unique'],
        'created_at' => ['default:current_timestamp'],
        'updated_at' => ['on_update:current_timestamp'],
    ];

    protected $fillable = ['name', 'email'];
}
