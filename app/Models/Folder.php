<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantIsolated;

class Folder extends Model
{
    use HasFactory, TenantIsolated;

    protected $fillable = [
        'name',
        'parent_id',
        'tenant_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'folder_id'); // Assuming File model exists
    }
}
