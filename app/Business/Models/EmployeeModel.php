<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class EmployeeModel extends Model
{
    use LogsActivity;
    protected $table = 'employee';
    protected $primaryKey = 'empid';

    protected $fillable = [
        'userId',
        'roid',
        'name',
        'mobile',
        'hasLogin',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }
    public function role(){
        return $this->hasOne('RoleModel','roid','roid');
    }

    public static function deleteValidation($id){
         return 0;//InvoiceModel::where('prid',$id)->count();
    }

    protected static $logName = 'EmployeeModel';
    protected static $logAttributes = [
        'empid',
        'userId',
        'roid',
        'name',
        'mobile',
        'hasLogin',
    ];
    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} EmployeeModel.";
    }
    protected static $logOnlyDirty = true;

}
