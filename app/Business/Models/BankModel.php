<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;
use User;
use InvoiceModel;

class BankModel extends Model
{
    use LogsActivity;

    protected $table = "bank";
    protected $primaryKey = "baid";

    protected $fillable = [
        'userId',
        'sid',
        'name',
        'ifsc',
        'branch',
        'accountNo',
        'accountName',
        'defaultBank',
    ];

    protected $hidden = ['created_at', 'updated_at'];
  
    protected static $logName = 'BankModel';

    protected static $logAttributes = [
        'baid',
        'userId',
        'sid',
        'name',
        'ifsc',
        'branch',
        'accountNo',
        'accountName',
        'defaultBank',
    ];

    protected static $recordEvents = ['created','updated','deleted'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} BankModel.";
    }

    protected static $logOnlyDirty = true;
  
     
    public function user(){
        return $this->hasOne('User','id','userId');
    }

    public static function deleteValidation($id){
        return InvoiceModel::where('baid',$id)->count();
    }
}
