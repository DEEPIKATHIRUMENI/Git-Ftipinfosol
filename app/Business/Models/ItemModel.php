<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class ItemModel extends Model
{
	use LogsActivity;
	protected $table = 'item';
	protected $primaryKey = 'itid';

	protected $fillable = [
		'userId',
		'name',
		'hsn',
		'gst',
		'purchaseRate',
		'invoiceRate',
		'openingStock',
	];
	protected $hidden = ['created_at', 'updated_at'];
	
    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }

    public static function deleteValidation($id){
         return InvoiceDetailModel::where('itid',$id)->count();
    }

    protected static $logName = 'ItemModel';
    protected static $logAttributes = [
        'itid',
		'userId',
		'name',
		'hsn',
		'gst',
		'purchaseRate',
		'invoiceRate',
		'openingStock',
    ];
	
    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} ItemModel.";
    }
    protected static $logOnlyDirty = true;
	
}
