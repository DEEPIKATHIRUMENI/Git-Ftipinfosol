<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class PurchaseModel extends Model
{
    use LogsActivity;
    protected $table = "purchase";
    protected $primaryKey = "purid";

    protected $fillable = [
        'userId',
        'prid',
        'type',
        'stockType',
        'no',
        'billNo',
        'date',
        'dueDate',
        'totalQuantity',
        'subTotal',
        'cgst',
        'sgst',
        'igst',
        'gstAmount',
        'roundOff',
        'totalAmount',
        'advanceAmount',
        'paidAmount',
        'balanceAmount',
        'narration',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at'];
   
    public function user(){
        return $this->hasOne('UserModel','id','userId');
    }
   
    public function party(){
        return $this->hasOne('PartyModel','prid','prid')->selectRaw("*, 
        (CASE
            WHEN mobile is null THEN companyName
            WHEN mobile is not null THEN concat(companyName,' - ',mobile)
        END) as partyDetails");
    }

    public function details(){
        return $this->hasMany('PurchaseDetailModel','purid','purid')->with('item');
    }


    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }

    public static function deleteValidation($id){
         return PaymentDetailModel::where('recipientId',$id)->count();
    }

    protected static $logName = 'PurchaseModel';
    protected static $logAttributes = [
        'purid',
        'userId',
        'prid',
        'type',
        'stockType',
        'no',
        'billNo',
        'date',
        'dueDate',
        'totalQuantity',
        'subTotal',
        'cgst',
        'sgst',
        'igst',
        'gstAmount',
        'roundOff',
        'totalAmount',
        'advanceAmount',
        'paidAmount',
        'balanceAmount',
        'narration',
        'status',
    ];
    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} PurchaseModel.";
    }
    protected static $logOnlyDirty = true;
}
