<?php

namespace App\Business\Controllers;
use Controller;
use PartyModel;
use InvoiceModel;
use InvoiceDetailModel;
use PaymentDetailModel;
use PaymentModel;
use PurchaseDetailModel;
use PurchaseModel;
use ExpenseModel;
use ItemModel;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function dashboard(Request $request)
    {

		 
		$expense=ExpenseModel::selectRaw("ifnull(sum(amount),0) as expense");
		if(isset($request->from)&&isset($request->from)){
			$expense->whereRaw("Date(date) between Date('$request->from') AND Date('$request->to')");
		}
		$data['expense']=$expense->first();


		$data['exp']=ExpenseModel::selectRaw('ifnull(sum(amount),0) as amount, type');
		if(isset($request->from)&&isset($request->from)){
			$data['exp']->whereRaw("Date(date) between Date('$request->from') AND Date('$request->to')");
		}
		$data['exp']=$data['exp']->groupBy('type')->get();


		$data['top_item']=InvoiceModel::leftJoin('invoice_detail', 'invoice_detail.invid', '=', 'invoice.invid')
		->leftJoin('item', 'item.itid', '=', 'invoice_detail.itid')
		->selectRaw('ifnull(sum(invoice_detail.quantity),0) as quantity,item.name');

		if(isset($request->from)&&isset($request->from)){
			$data['top_item']->whereRaw("Date(date) between Date('$request->from') AND Date('$request->to')");
		}
		$data['top_item']=$data['top_item']
		->groupBy('item.itid')->orderBy('quantity','asc')
		->take(10)->get();

		$data['top_customer']=InvoiceModel::
		leftJoin('party', 'party.prid', '=', 'invoice.prid')
		->selectRaw('*,ifnull(sum(totalAmount),0) as totalAmount,party.companyName')
		->whereNotNull('party.prid');

		if(isset($request->from)&&isset($request->from)){
			$data['top_customer']->whereRaw("Date(date) between Date('$request->from') AND Date('$request->to')");
		}

		$data['top_customer']=$data['top_customer']
		->groupBy('party.prid')->orderBy('totalAmount','asc')
		->take(10)->get();


		$data['salesSummary']=InvoiceModel::selectRaw("ifnull(sum(totalAmount),0) as Invoice, date")
		->groupBy(DB::raw('YEAR(date), MONTH(date)'))
		->get();
	
		return response($data);
    }





}