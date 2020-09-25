<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property string $uid
 * @property string $fecha
 * @property string $from
 * @property string $subject
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Email extends Model
{
    use SoftDeletes;

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';
    
    protected $table = 'mails';
    
    /**
     * @var array
     */
    protected $fillable = ['uid', 'fecha', 'from', 'subject', 'created_at', 'updated_at', 'deleted_at'];

}
