<?php

namespace App\Business\Miscs;
use Auth;
use Hash;
use Carbon;
use PermissionModel;

class Helper
{
    public static function msgEnt($columnName)
    {
        return "Please enter " . $columnName . " here";
    }

    public static function msgSel($columnName)
    {
        return "Please select " . $columnName . " here";
    }

    public static function msgAl($columnName)
    {
        return $columnName . " already exist";
    }

    public static function msgDl($columnName)
    {
        return $columnName . ' cannot be deleted. Because it is already associated with other records.';
    }

    public static function passwordEncode($password)
    {
        return Hash::make($password);
    }   


    public static function getBrowser() {
        $user_agent =   $_SERVER['HTTP_USER_AGENT'];
        $browser = "Unknown Browser";
        $browser_array  = array('/msie/i'=>'Internet Explorer','/firefox/i'=>'Firefox','/safari/i'=>'Safari','/mobile/i'=>'Handheld Browser','/chrome/i'=>'Chrome','/edge/i'=>'Edge','/opera/i'=>  'Opera','/netscape/i'=>'Netscape','/maxthon/i'=>'Maxthon','/konqueror/i'=>'Konqueror');
        foreach ($browser_array as $regex => $value) { 
            if (preg_match($regex, $user_agent)) {
                $browser    =   $value;
            }
        }
        return $browser;
    }

    public static function getIpAddress() {
        return  $_SERVER["REMOTE_ADDR"];
    }

    public static function setFinancialYear($date, $Model, $type)
    {
        $unixTimestamp = strtotime($date);
        $timeStamp = Carbon::createFromTimestamp($unixTimestamp);
        $from = clone ($timeStamp);
        $to = clone ($timeStamp);
        if ($timeStamp->format('m') <= 3) {
            $from = Carbon::parse('01-04-' . $from->addYear(-1)->format('Y'));
            $to = Carbon::parse('01-04-' . $to->format('Y'));
        } else {
            $from = Carbon::parse('01-04-' . $from->format('Y'));
            $to = Carbon::parse('01-04-' . $to->addYear(1)->format('Y'));
        }

        $getNo = $Model::select("*");
        if(isset($data)){
            $getNo->whereRaw("DATE(date) BETWEEN DATE('$from') AND DATE('$to')");
        }
        if($Model=='InvoiceModel'||$Model=='PaymentModel'||$Model=='PurchaseModel'){
            $getNo->where('type',$type);
        }
        
        $getNo = $getNo->orderBy('no', 'DESC')->first();
       
        if (isset($getNo)) {
            $no = $getNo->no + 1;
        } else {
            ($Model=='InvoiceModel')? $no = Auth::user()->setting->invoiceStartFrom : $no = 1;
        }
        return $no;
    }

    public static function printFormatHeight($count)
    {
        if ($count <= 19) {
            $height = 500 - (17 * $count);
        }
        if ($count > 19 && $count < 39) {
            $height = 500 - (17 * ($count - 19));
        }
        if ($count > 38) {
            $height = 500 - (17 * ($count - 38));
        }
        return $height;
    }

    public static function numberToText($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }

        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
        "." . $words[$point / 10] . " " .
        $words[$point = $point % 10] : '';

        return (isset($points) && $points != '') ? $result . "Rupees  " . $points . " Paise Only." : $result . "Rupees  " . $points . " Only.";
    }


    public static function sidebarMenu()
    {

        $menu = [
            [
                'name' => "Dashboard","icon" => "business",
                'menu' => [
                    [
                        "id" => MENU_DASHBOARD,
                        "name" => "Dashboard",
                        "url" => "home.dashboard",
                        "icon" => "business",
                        "active" => 0,
                        "action" =>
                        [
                            
                        ],
                    ],
                ],
            ],
            [
                'name' => "Business","icon" => "sales",
                'menu' => [
                    
                    [
                        "id" => MENU_INVOICE,
                        "name" => "Estimate",
                        "url" => "home.invoice",
                        "icon" => "invoice",
                        "active" => 0,
                        "action" =>
                        [
                            ["id" => ACTION_INVOICE_CREATE, "name" => "Create", "active" => 0],
                            ["id" => ACTION_INVOICE_EDIT, "name" => "Edit", "active" => 0],
                            ["id" => ACTION_INVOICE_DELETE, "name" => "Delete", "active" => 0],
                            ["id" => ACTION_INVOICE_PRINT, "name" => "Print", "active" => 0],
                        ],
                    ],
                    [
                        "id" => MENU_PURCHASE,
                        "name" => "Stock Entry",
                        "url" => "home.purchase",
                        "icon" => "purchase",
                        "active" => 0,
                        "action" =>
                        [
                            ["id" => ACTION_PURCHASE_CREATE, "name" => "Create", "active" => 0],
                            ["id" => ACTION_PURCHASE_EDIT, "name" => "Edit", "active" => 0],
                            ["id" => ACTION_PURCHASE_DELETE, "name" => "Delete", "active" => 0],
                            ["id" => ACTION_PURCHASE_PRINT, "name" => "Print", "active" => 0],

                        ],
                    ],
                    [
                        "id" => MENU_EXPENSE,
                        "name" => "Expense",
                        "url" => "home.expense",
                        "icon" => "expense",
                        "active" => 0,
                        "action" =>
                        [
                            ["id" => ACTION_EXPENSE_CREATE, "name" => "Create", "active" => 0],
                            ["id" => ACTION_EXPENSE_EDIT, "name" => "Edit", "active" => 0],
                            ["id" => ACTION_EXPENSE_DELETE, "name" => "Delete", "active" => 0],
                        ],
                    ],
                ],
            ],
           
            [
                'name' => "Reports","icon" => "business",
                'menu' => [
                    [
                        "id" => MENU_STOCK_STATEMENT,
                        "name" => "Stock Statement",
                        "url" => "home.stockStatement",
                        "icon" => "stock",
                        "active" => 0,
                        "action" =>[],
                    ],
                    [
                        "id" => MENU_STOCK_LEDGER,
                        "name" => "Stock Ledger",
                        "url" => "home.stockLedger",
                        "icon" => "stock",
                        "active" => 0,
                        "action" =>[],
                    ],
                    [
                        "id" => MENU_SALES_GST_STATEMENT,
                        "name" => "Sales Statement",
                        "url" => "home.salesStatement",
                        "icon" => "business",
                        "active" => 0,
                        "action" =>[],
                    ],
                    // [
                    //     "id" => MENU_PURCHASE_GST_STATEMENT,
                    //     "name" => "Purchase GST Statement",
                    //     "url" => "home.purchaseStatement",
                    //     "icon" => "business",
                    //     "active" => 0,
                    //     "action" =>[],
                    // ],
                    // [
                    //     "id" => MENU_CUSTOMER_LEDGER,
                    //     "name" => "customer Ledger",
                    //     "url" => "home.customerLedger",
                    //     "icon" => "summary",
                    //     "active" => 0,
                    //     "action" =>[],
                    // ],
                    // [
                    //     "id" => MENU_SUPPLIER_LEDGER,
                    //     "name" => "Supplier Ledger",
                    //     "url" => "home.supplierLedger",
                    //     "icon" => "summary",
                    //     "active" => 0,
                    //     "action" =>[],
                    // ],
                    // [
                    //     "id" => MENU_CUSTOMER_OUTSTANDING,
                    //     "name" => "Customer Outstanding",
                    //     "url" => "home.customerOutstanding",
                    //     "icon" => "outstanding",
                    //     "active" => 0,
                    //     "action" =>[],
                    // ],
                    // [
                    //     "id" => MENU_SUPPLIER_OUTSTANDING,
                    //     "name" => "Supplier Outstanding",
                    //     "url" => "home.supplierOutstanding",
                    //     "icon" => "outstanding",
                    //     "active" => 0,
                    //     "action" =>[],
                    // ],
                    // [
                    //     "id" => MENU_HSN_SUMMARY,
                    //     "name" => "HSN Summary",
                    //     "url" => "home.hsnSummary",
                    //     "icon" => "summary",
                    //     "active" => 0,
                    //     "action" =>[],
                    // ],
                    [
                        "id" => MENU_EXPENSE_REPORT,
                        "name" => "Expense Report",
                        "url" => "home.expenseReport",
                        "icon" => "expense",
                        "active" => 0,
                        "action" =>[],
                    ],
                  
                   
                ],
            ],
            ['name' => "Master","icon" => "master",
                'menu' =>
                [
                    [
                        "id" => MENU_PARTY,
                        "name" => "Customer",
                        "url" => "home.party",
                        "icon" => "party",
                        "active" => 0,
                        "action" =>
                        [
                            ["id" => ACTION_PARTY_CREATE, "name" => "Create", "active" => 0],
                            ["id" => ACTION_PARTY_EDIT, "name" => "Edit", "active" => 0],
                            ["id" => ACTION_PARTY_DELETE, "name" => "Delete", "active" => 0],
                        ],
                    ],
                    [
                        "id" => MENU_ITEM,
                        "name" => "item",
                        "url" => "home.item",
                        "icon" => "item",
                        "active" => 0,
                        "action" =>
                        [
                            ["id" => ACTION_ITEM_CREATE, "name" => "Create", "active" => 0],
                            ["id" => ACTION_ITEM_EDIT, "name" => "Edit", "active" => 0],
                            ["id" => ACTION_ITEM_DELETE, "name" => "Delete", "active" => 0],
                        ],
                    ],
                    // [
                    //     "id" => MENU_UNIT,
                    //     "name" => "unit",
                    //     "url" => "home.unit",
                    //     "icon" => "unit",
                    //     "active" => 0,
                    //     "action" =>
                    //     [
                    //         ["id" => ACTION_UNIT_CREATE, "name" => "Create", "active" => 0],
                    //         ["id" => ACTION_UNIT_EDIT, "name" => "Edit", "active" => 0],
                    //         ["id" => ACTION_UNIT_DELETE, "name" => "Delete", "active" => 0],
                    //     ],
                    // ],  
                    // [
                    //     "id" => MENU_EMPLOYEE,
                    //     "name" => "employee",
                    //     "url" => "home.employee",
                    //     "icon" => "employee",
                    //     "active" => 0,
                    //     "action" =>
                    //     [
                    //         ["id" => ACTION_EMPLOYEE_CREATE, "name" => "Create", "active" => 0],
                    //         ["id" => ACTION_EMPLOYEE_EDIT, "name" => "Edit", "active" => 0],
                    //         ["id" => ACTION_EMPLOYEE_DELETE, "name" => "Delete", "active" => 0],
                    //     ],
                    // ],
                    // [
                    //     "id" => MENU_ROLE,
                    //     "name" => "role",
                    //     "url" => "home.role",
                    //     "icon" => "role",
                    //     "active" => 0,
                    //     "action" =>
                    //     [
                    //         ["id" => ACTION_ROLE_CREATE, "name" => "Create", "active" => 0],
                    //         ["id" => ACTION_ROLE_EDIT, "name" => "Edit", "active" => 0],
                    //         ["id" => ACTION_ROLE_DELETE, "name" => "Delete", "active" => 0],
                    //     ],
                    // ],
                    // [
                    //     "id" => MENU_USER,
                    //     "name" => "user",
                    //     "url" => "home.user",
                    //     "icon" => "user",
                    //     "active" => 0,
                    //     "action" =>
                    //     [
                    //         ["id" => ACTION_USER_CREATE, "name" => "Create", "active" => 0],
                    //         ["id" => ACTION_USER_EDIT, "name" => "Edit", "active" => 0],
                    //         ["id" => ACTION_USER_DELETE, "name" => "Delete", "active" => 0],
                    //     ],
                    // ],
                    // [
                    //     "id" => MENU_PERMISSION,
                    //     "name" => "permission",
                    //     "url" => "home.permission",
                    //     "icon" => "permission",
                    //     "active" => 0,
                    //     "action" =>
                    //     [
                    //     ],
                    // ],
                    [
                        "id" => MENU_SETTING,
                        "name" => "settings",
                        "url" => "home.setting.profile",
                        "icon" => "setting",
                        "active" => 0,
                        "action" =>
                        [
                        ],
                    ],
                ],
            ],
        ];

        $customMenu=$menu;

        $user = Auth::user();
        $menuPermission = PermissionModel::where('type', 1)->where('roid', $user->roid)->get();
        $actionPermission = PermissionModel::where('type', 2)->where('roid', $user->roid)->get();

        foreach ($customMenu as $key1 => $mn) {
            foreach ($mn['menu'] as $key2 => $m) {
                foreach ($menuPermission as $key3 => $men) {
                    if ($m['id'] == $men['typeId']) {
                        $customMenu[$key1]['menu'][$key2]['active'] = 1;
                    }
                }
                foreach ($m['action'] as $key4 => $a) {
                    foreach ($actionPermission as $act) {
                        if ($a['id'] == $act['typeId']) {
                            $customMenu[$key1]['menu'][$key2]['action'][$key4]['active'] = 1;
                        }
                    }
                }
            }
        }

        foreach ($customMenu as $key5 => $mn) {
            foreach ($mn['menu'] as $key6 => $m) {
                if ($m['active'] == 0) {
                    unset($customMenu[$key5]['menu'][$key6]);
                }
            }
        }


        $data['menu'] = $menu;
        $data['customMenu'] = $customMenu;

        return $data;
    }
}
