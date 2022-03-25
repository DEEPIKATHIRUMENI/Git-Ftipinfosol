<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class PurchaseDetailModel extends Model
{
    use LogsActivity;
    protected $table = "purchase_detail";
    protected $primaryKey = "purdid";

    protected $fillable = [
        'userId',
        'purid',
        'itid',
        'uid',
        'gst',
        'hsn',
        'quantity',
        'rate',
        'subTotal',
        'cgst',
        'sgst',
        'igst',
        'gstAmount',
        'totalAmount',
    ];

    protected $hidden = ['created_at', 'updated_at'];
    
    public function item(){
        return $this->hasOne('ItemModel','itid','itid');
    }


    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }

    public static function deleteValidation($id){
         return 0;
    }

    protected static $logName = 'PurchaseDetailModel';
    protected static $logAttributes = [
        'purdid',
        'userId',
        'purid',
        'itid',
        'uid',
        'gst',
        'hsn',
        'quantity',
        'rate',
        'subTotal',
        'cgst',
        'sgst',
        'igst',
        'gstAmount',
        'totalAmount',
    ];
    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} PurchaseDetailModel.";
    }
    protected static $logOnlyDirty = true;

}
