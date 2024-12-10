<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrol extends Model
{
    use HasFactory;
    protected $table = 'patrols';
    protected $primaryKey = 'patrol_id';
    protected $fillable = [
        'tanggal',
        'divisi_id',
        'user_id',
        'temuan',
        'dokumentasi',
        'status',
        'created_at',
        'updated_at',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'divisi_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); // 'id' adalah primary key di tabel users
    }
}
