<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'perbaikans';
    protected $primaryKey = 'perbaikan_id';
    protected $fillable = [
        'patrol_id',
        'temuan',
        'keterangan',
        'dokumentasi',
        'perbaikan',
        'target',
        'user_id',
        'dokumentasi',
        'status',
        'revisi',
        'created_at',
        'updated_at',
    ];
    public function patrol()
    {
        return $this->belongsTo(Patrol::class, 'patrol_id', 'patrol_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // 'id' adalah primary key di tabel users
    }
}
