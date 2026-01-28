<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;

    protected $fillable = ['entity_type', 'entity_id', 'notes', 'attachment', 'internal', 'shared_with_customer', 'create_by'];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function entity()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id');
    }

    public function attachments()
    {
        return $this->hasMany(note_attachments::class, 'entity_id');
    }
}
