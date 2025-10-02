<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'writer_id',
        'content',
        'contacted_at'
    ];

    protected $appends = ['writer_name'];

    protected $casts = ['contacted_at' => 'datetime:d-m-Y H:ia'];

    public function writer () {
        return $this->belongsTo(User::class, 'writer_id');
    }

    public function getWriterNameAttribute() {
        // return $this->writer->name;
    }
}
