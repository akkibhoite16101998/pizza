<?php

namespace App\Http\Controllers\bill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Fund;
use App\Models\Fund_expenses;

class FundController extends Controller
{
    //
    public function fundView(){

        $adminList = DB::table('users')->select('name','id')->where('role','admin')->get();
        #echo "<pre>";print_r($adminList);die();
        return view('fund/add_fund_view',['adminList'=>$adminList]);
    }

    public function store_fund(Request $req){

        #echo "<pre>";print_r($_POST);die();

        $rules = [
            'admin_user' => 'required | numeric',
            'amount'      => 'required | numeric| min:10',
            'pay_mode'   => 'in:UPI,cash',
            'add_remark' => 'required'
        ];

        if($req->payment_image !=""){
 
            $rules['payment_image'] =  'image';

        }

        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
            return redirect()->route('add_fund_view')->withInput()->withErrors($validator);

        }

        $fund = new Fund();
        $fund->u_id = $req->admin_user;
        $fund->amount = $req->amount;
        $fund->mode = $req->pay_mode;
        $fund->remarks = $req->add_remark;
        $fund->date = date('Y-m-d');

        if ($req->hasFile('payment_image')) {

            $image = $req->file('payment_image'); 
            $ext = $image->getClientOriginalExtension(); 
            $image_name = 'fund_' . time() . '.' . $ext;
            $image->move(public_path('dashboard/images/fund-img'), $image_name);
            $fund->image = $image_name;
        }
        
        $fund->save();
        return redirect()->route('add_fund_view')->with('success','Fund Amount Added Successfully ');

    }

    public function expensfundView()
    {
        $adminList = DB::table('users')->select('name','id')->where('role','admin')->get();
        #echo "<pre>";print_r($adminList);die();
        return view('fund/expens_fund_view',['adminList'=>$adminList]);

    }

    public function store_expens_fund(Request $req){

        // echo "<pre>";print_r($_POST);echo"<br>";
        // print_r($_FILES);die();
        $rules = [
            'expens_item' => 'required',
            'admin_user' => 'required | numeric',
            'amount'      => 'required | numeric| min:10',
            'pay_mode'   => 'in:UPI,cash',
            'reason' => 'required'
        ];

        if($req->payment_image !=""){
 
            $rules['payment_image'] =  'image';

        }

        $validator = Validator::make($req->all(), $rules);

        if($validator->fails())
        {
            return redirect()->route('expens_fund_view')->withInput()->withErrors($validator);

        }

        $ex_fund = new Fund_expenses();
        $ex_fund->u_id = $req->admin_user;
        $ex_fund->amount = $req->amount;
        $ex_fund->mode = $req->pay_mode;
        $ex_fund->reason = $req->reason;
        $ex_fund->date = date('Y-m-d');

        if ($req->hasFile('payment_image')) {

            $image = $req->file('payment_image'); 
            $ext = $image->getClientOriginalExtension(); 
            $image_name = 'fund_' . time() . '.' . $ext;
            $image->move(public_path('dashboard/images/fund-img'), $image_name);
            $ex_fund->expense_image = $image_name;
        }
        
        $ex_fund->save();
        return redirect()->route('expens_fund_view')->with('success','Expens Amount Added Successfully ');

    }
}
