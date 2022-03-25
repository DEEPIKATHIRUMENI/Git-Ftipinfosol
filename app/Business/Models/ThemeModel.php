<?php

namespace App\Business\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;
use User;
use BankModel;

class ThemeModel extends Model
{
    use LogsActivity;

    protected $table = "theme";
    protected $primaryKey = "thid";

    protected $fillable = [
        'userId',
        'sid',
        'headerFixed',
        'asideFixed',
        'asideFolded',
        'asideDock',
        'container',
        'customTheme',
        'customPrimaryDark',
        'customPrimary',
        'customAccent',
        'themeID',
        'themes',
    ];

    protected $hidden = ['created_at', 'updated_at'];
    
    public function user(){
        return $this->hasOne('User','id','userId');
    }

    public function save(array $options = array()){
        if (!$this->userId) {
            $this->userId = Auth::user()->id;
        }
        parent::save($options);
    }

    protected static $logName = 'ThemeModel';
    protected static $logAttributes = [
        'thid',
        'userId',
        'sid',
        'headerFixed',
        'asideFixed',
        'asideFolded',
        'asideDock',
        'container',
        'customTheme',
        'customPrimaryDark',
        'customPrimary',
        'customAccent',
        'themeID',
        'themes',
    ];

    protected static $recordEvents = ['created','updated','deleted'];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "You Have {$eventName} ThemeModel.";
    }

    protected static $logOnlyDirty = true;

}
