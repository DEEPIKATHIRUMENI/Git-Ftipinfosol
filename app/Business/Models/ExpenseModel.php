<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class ExpenseModel extends Model
{
    use LogsActivity;
    protected $table = 'expense';
    protected $primaryKey = 'exid';

    protected $fillable = [
        'userId',
        'date',
        'type',
        'amount',
        'details',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }

    public static function deleteValidation($id){
         return 0;
    }

    protected static $logName = 'ExpenseModel';
    protected static $logAttributes = [
        'exid',
        'userId',
        'date',
        'type',
        'amount',
        'details',
    ];
    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} ExpenseModel.";
    }
    protected static $logOnlyDirty = true;
	
}
