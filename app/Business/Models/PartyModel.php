<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class PartyModel extends Model
{
    use LogsActivity;
    protected $table = 'party';
    protected $primaryKey = 'prid';

    protected $fillable = [
        'userId',
        'partyType',
        'companyName',
        'customerName',
        'mobile',
        'email',
        'addressLine1',
        'addressLine2',
        'city',
        'pin',
        'stateCode',
        'gstin',
        'openingBalance',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }

    public static function deleteValidation($id){
         return InvoiceModel::where('prid',$id)->count()+PurchaseModel::where('prid',$id)->count();
    }

    protected static $logName = 'PartyModel';
    protected static $logAttributes = [
        'prid',
        'partyType',
        'userId',
        'companyName',
        'customerName',
        'mobile',
        'email',
        'addressLine1',
        'addressLine2',
        'city',
        'pin',
        'stateCode',
        'gstin',
        'openingBalance',
    ];
    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} PartyModel.";
    }
    protected static $logOnlyDirty = true;
	
}
