<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class InvoiceModel extends Model
{
    use LogsActivity;
    protected $table = "invoice";
    protected $primaryKey = "invid";

    protected $fillable = [
        'userId',
        'prid',
        'type',
        'no',
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
            WHEN mobile is null THEN customerName
            WHEN mobile is not null THEN concat(customerName,' - ',mobile)
        END) as partyDetails");
    }

    public function details(){
        return $this->hasMany('InvoiceDetailModel','invid','invid')->with('item');
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

    protected static $logName = 'InvoiceModel';
    protected static $logAttributes = [
        'invid',
        'userId',
        'prid',
        'type',
        'no',
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
        return "You Have {$eventName} InvoiceModel.";
    }
    protected static $logOnlyDirty = true;
}
