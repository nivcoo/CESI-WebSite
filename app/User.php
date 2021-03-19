<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'permissions', 'birth_date', 'center', 'promotion', 'rank_id'];

    public function create(array $attributes = [])
    {
        return parent::create($attributes); // TODO: Change the autogenerated stub
    }

    public function find($id, $columns = ['*'])
    {
        return parent::find($id, $columns); // TODO: Change the autogenerated stub
    }

    public function get($columns = ['*'])
    {
        return parent::get($columns); // TODO: Change the autogenerated stub
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        return parent::where($column, $operator, $value, $boolean); // TODO: Change the autogenerated stub
    }

    public function count($columns = '*')
    {
        return parent::count($columns); // TODO: Change the autogenerated stub
    }

}