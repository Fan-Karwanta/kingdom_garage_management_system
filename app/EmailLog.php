<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $subject
 * @property string $recipient_email
 * @property string $content
 */
class EmailLog extends Model
{
    use HasFactory;

    protected $table = 'email_logs';
}
