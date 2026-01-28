<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $note_id
 * @property int $entity_id
 * @property string $attachment
 *
 * ... add other columns
 */
class note_attachments extends Model
{
    use HasFactory;

    protected $fillable = ['entity_id', 'attachment'];

    public function note()
    {
        return $this->belongsTo(Notes::class, 'entity_id');
    }
}
