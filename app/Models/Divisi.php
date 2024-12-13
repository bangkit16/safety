<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use HasFactory;
    protected $table = 'divisis';
    protected $primaryKey = 'divisi_id';
    protected $fillable = [
        'nama',
        'created_at',
        'updated_at',
    ];
    public function divisi():HasMany{
        return $this->hasMany(User::class, 'divisi_id', 'divisi_id');
    }
}
