<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;
use User;
use EstimateModel;
use InvoiceModel;
use ExpenseModel;

class BranchModel extends Model
{
    use LogsActivity;

    protected $table = "branch";
    protected $primaryKey = "brid";

    protected $fillable = [
        'userId',
        'sid',
        'name',
        'type',
    ];

    protected $hidden = ['created_at', 'updated_at'];
    
    protected static $logName = 'BranchModel';

    protected static $logAttributes = [
        'brid',
        'userId',
        'sid',
        'name',
    ];

    protected static $recordEvents = ['created','updated','deleted'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} BranchModel.";
    }

    protected static $logOnlyDirty = true;

    public function user(){
        return $this->hasOne('User','id','userId');
    }

    public static function deleteValidation($id){
        return EstimateModel::where('brid',$id)->count()+InvoiceModel::where('brid',$id)->count()+ExpenseModel::where('brid',$id)->count();
    }
}
