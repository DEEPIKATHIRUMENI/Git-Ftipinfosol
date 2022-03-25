<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;
use EmployeeModel;
use UserModel;

class RoleModel extends Model
{
    use LogsActivity;

    protected $table = "role";
    protected $primaryKey = "roid";

    protected $fillable = [
        'userId',
        'name',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public static function deleteValidation($id){
        return EmployeeModel::where('roid',$id)->count()+
        UserModel::where('roid',$id)->count();
     }

    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }

    protected static $logName = 'RoleModel';
    protected static $logAttributes = [
        'roid',
        'userId',
        'name',
    ];
    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} RoleModel.";
    }
    protected static $logOnlyDirty = true;
}
