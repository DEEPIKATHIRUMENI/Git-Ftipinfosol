<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;
use User;
use BankModel;

class SettingModel extends Model
{
    use LogsActivity;

    protected $table = "setting";
    protected $primaryKey = "sid";

    protected $fillable = [
        'userId',
        'name',
        'mobile1',
        'mobile2',
        'email',
        'gstin',
        'addressLine1',
        'addressLine2',
        'city',
        'pin',
        'stateCode',
        'invoiceStartFrom',
        'receiptSize',
        'logo',
        'estimateTerms',
        'invoiceTerms',
        'billTerms',
        'imageQuality',
        'zoom',
    ];

    protected $hidden = ['created_at', 'updated_at'];
    
    public function user(){
        return $this->hasOne('User','id','userId');
    }
    public function bank(){
        return $this->hasMany('BankModel','sid','sid');
    }

    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }

    protected static $logName = 'SettingModel';
    protected static $logAttributes = [
        'sid',
        'userId',
        'name',
        'mobile1',
        'mobile2',
        'email',
        'gstin',
        'addressLine1',
        'addressLine2',
        'city',
        'pin',
        'stateCode',
        'invoiceStartFrom',
        'receiptSize',
        'logo',
        'estimateTerms',
        'invoiceTerms',
        'billTerms',
    ];

    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} SettingModel.";
    }

    protected static $logOnlyDirty = true;

}
