<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Messages extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_uuid',
        'message',
        'connection_id',
        'username',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
    ];

    protected $sortable = [
        'created_at',
    ];

    public function users()
    {
        return $this->hasOne(User::class, 'user_uuid');
    }

    public function store(array $data): object
    {
        return $this->create($data);
    }

    public function getAll()
    {
        return $this->select('message', 'username',DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y %h:%i:%s") as created'), 'user_id')->oldest()->get();
    }
}
