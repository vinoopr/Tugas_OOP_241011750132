<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public const STATUSES = ['Active', 'Selesai', 'In Progress'];

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return str_starts_with($this->image, 'projects/')
            ? asset('storage/'.$this->image)
            : asset('images/'.$this->image);
    }

    protected $table = 'projects';

    protected $fillable = [
        'nama_project',
        'description',
        'teknologi',
        'image',
        'status',
    ];
}
