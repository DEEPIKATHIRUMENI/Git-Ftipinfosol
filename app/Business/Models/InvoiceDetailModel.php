<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class InvoiceDetailModel extends Model
{
    use LogsActivity;
    protected $table = "invoice_detail";
    protected $primaryKey = "invdid";

    protected $fillable = [
        'userId',
        'invid',
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

    protected static $logName = 'InvoiceDetailModel';
    protected static $logAttributes = [
        'invdid',
        'userId',
        'invid',
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
        return "You Have {$eventName} InvoiceDetailModel.";
    }
    protected static $logOnlyDirty = true;

}
