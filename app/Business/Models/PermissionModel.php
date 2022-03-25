<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class PermissionModel extends Model
{
    use LogsActivity;

    protected $table = "permission";
    protected $primaryKey = "peid";

    protected $fillable = [
        'userId',
        'roid',
        'type',
        'typeId',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function save(array $options = array()) {
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }
    protected static $logName = 'PermissionModel';
    protected static $logAttributes = [
        'peid',
        'userId',
        'roid',
        'type',
        'typeID',
    ];
    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} PermissionModel.";
    }
    protected static $logOnlyDirty = true;
    
}
