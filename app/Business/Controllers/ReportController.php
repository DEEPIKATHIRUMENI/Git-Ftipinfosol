<?php

namespace App\Business\Controllers;
use Controller;
use PartyModel;
use InvoiceModel;
use PaymentModel;
use InvoiceDetailModel;
use PurchaseDetailModel;
use PurchaseModel;
use ExpenseModel;
use ItemModel;
use DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function salesStatement(Request $request)
    {
        $data=InvoiceModel::select('*')->with('party');
		if(isset($request->from)&&isset($request->to)){
			$data->whereRaw("Date(date) between '$request->from' AND '$request->to'");
		}
		if(isset($request->prid)){
			$data->where('prid',$request->prid);
		}
		$data=$data->orderBy('date','asc')->orderBy('no','asc')->get();
		return response($data);
    }

	public function expenseReport(Request $request)
    {
		$report=ExpenseModel::select('*');
		if(isset($request->from)&&isset($request->from)){
			$report->whereRaw("Date(date) between '$request->from' AND '$request->to'");
		}
		if(isset($request->type)){
			$report->where('type',$request->type);
		}
		$report=$report->get();
		$data['report']=$report;
		return response($data);
    }
	 
	public function stockStatement(Request $request)
    {
        $data=ItemModel::selectRaw("*,
		(ifnull((openingStock),0) +
		ifnull((SELECT SUM(quantity) FROM purchase_detail WHERE itid=item.itid),0) -
		ifnull((SELECT SUM(quantity) FROM invoice_detail WHERE itid=item.itid),0)) as totalQuantity");
		$data=$data->get();
		return response($data);
    }

	public function stockLedger(Request $request)
    {
        $openingStock=ItemModel::selectRaw('ifnull((openingStock),0) As opening')->find($request->itid);

		$invoice=InvoiceDetailModel::leftjoin('invoice','invoice.invid','=','invoice_detail.invid')
		->selectRaw('ifnull(SUM(quantity),0) As quantity')
		->where('itid',$request->itid)
		->whereRaw("DATE(date) < DATE('$request->from')")
		->first();
		
		$purchase=PurchaseDetailModel::leftjoin('purchase','purchase.purid','=','purchase_detail.purid')
		->selectRaw('ifnull(SUM(quantity),0) As quantity')
		->where('itid',$request->itid)
		->whereRaw("DATE(date) < DATE('$request->from')")
		->first();

		$report=DB::select("SELECT * 
		FROM (
			(SELECT 'Estimate' As Type, invoice_detail.quantity As Inv, '' AS Rec, invoice_detail.itid, invoice.date as date, invoice.no As No 
			FROM invoice_detail
			LEFT JOIN invoice on invoice.invid = invoice_detail.invid
			)
		    UNION ALL
		    (SELECT 'Purchase' As Type, '' AS Inv, purchase_detail.quantity As Rec, purchase_detail.itid, purchase.date as date, purchase.no As No  
			FROM purchase_detail
			LEFT JOIN purchase on purchase.purid = purchase_detail.purid
			)
		) results WHERE itid = $request->itid AND DATE(date) BETWEEN DATE('$request->from') AND DATE('$request->to') ORDER BY date ASC");


		$data['Report']=$report;
		$data['Beginning']=$openingStock->opening+($purchase->quantity-$invoice->quantity);

		return response($data);
    }


}
