<?php

namespace App\Http\Controllers\bill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
#
use App\Models\Order;
use App\Models\Order_items;
use App\Models\Menus;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Support\Facades\Log;

#

use function Laravel\Prompts\select;

class BillController extends Controller
{
    //

    public function orderView(){ 

        $menu_list = DB::table('menus')
        ->select('id','name','price','image')
        ->where('status','active')
        ->get();

        #echo "<pre>";print_r($menu_list);die();
        return view('dashboard.orderView',['data'=>$menu_list]);
    }

    public function menulist(){

        $menu_list = DB::table('menus')
        ->select('id','name','price','image','status')
        ->paginate(4);

        return view('dashboard.menulist', ['data'=>$menu_list]);
    }

    public function create_order(Request $req)
    {
        $rules = [
            'grandTotal' => 'required|numeric|min:10',
            'phone' => 'nullable|string',
            'payMode' => 'required|string',
            'cart' => 'required|array',
            'cart.*.id' => 'required|integer',
            'cart.*.name' => 'required|string',
            'cart.*.count' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
        ];
    
        $validator = Validator::make($req->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->route('order')->with('error', 'Invalid order details. Please check again!');
        }
        
        $userId = Auth::user()->id;
        $order = new Order();
        $order->grand_total = $req->grandTotal;
        $order->phone = $req->phone ?? "";
        $order->pay_mode = $req->payMode;
        $order->u_id = $userId;
        $order->save(); 
    
        $grandTotal = 0;
        $disc_amt = 0;
    
        foreach ($req->cart as $item) 
        {
            $order_items = new Order_items();
            $order_items->order_id = $order->id;
            $order_items->menu_id = $item['id'];
            $order_items->price = $item['price'];
            $order_items->quantity = $item['count'];
            $order_items->total = $item['count'] * $item['price'];
            $order_items->u_id = $userId;
            $order_items->save();
    
            $grandTotal += $order_items->total;
        }
    
        $order->grand_total = $grandTotal;
        $order->disc_amt = $disc_amt;
        $order->paid_amt = $grandTotal - $disc_amt;
        $order->save();

        $this->print_invoice($order->id);
    
        return redirect()->route('order')->with('success', 'Order created successfully!');
    }

    public function print_invoice($order_id)
    {
        $order = Order::with('order_items')->find($order_id);
        if (!$order) {
            return;
        }

        try {

            // प्रिंटर का नाम सेट करें (Windows में Control Panel से चेक करें)
            $connector = new WindowsPrintConnector("POS_PRINTER");
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Pizzoteria\n");
            $printer->text("Main Branch\n");
            $printer->text("----------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->text("Bill No: " . $order->id . "\n");
            $printer->text("Date: " . date('d-m-Y H:i', strtotime($order->created_at)) . "\n");
            $printer->text("Payment Mode: " . $order->pay_mode . "\n");
            $printer->text("----------------------------\n");

            foreach ($order->order_items as $item) {
                $printer->text($item->menu->name . "\n");
                $printer->text($item->quantity . " x " . $item->price . " = " . $item->total . "\n");
            }

            $printer->text("----------------------------\n");
            $printer->text("Grand Total: " . $order->grand_total . "\n");
            $printer->text("Thank You for Visiting!\n");

            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            Log::error("Printer Error: " . $e->getMessage());
        }
    }

    public function menu_store(Request $req){

        //return $req;
        #echo"<pre>";print_r($_POST);print_r($_FILES);die();
        $rules = [

            'menu_name' => 'required | string',
            'price'     => 'required | numeric | min:10',
            'status' => 'required|in:active,deactive',

        ];

        if($req->menu_image !=""){
 
            $rules['menu_image'] =  'image';

        }
        /*
        if ($req->hasFile('menu_image')) {
            $rule['menu_image'] = 'image|mimes:jpeg,png,jpg,gif,webp';
        } */
        

        $validator = Validator::make($req->all(),$rules);

        if($validator->fails()){

            return redirect()->route('menu_add')->withInput()->withErrors($validator);

        }

        $menu = new Menus();
        $menu->name = $req->menu_name;
        $menu->price = $req->price;
        $menu->status = $req->status;

        if($req->menu_image != "")
        {
            $image = $req->menu_image;
            $ext = $image->getClientOriginalExtension();
            $menu_name = str_replace(' ', '_', strtolower($req->menu_name));
            $image_name = $menu_name . '_' . time() . '.' . $ext;
            // Save image in menu directory
            $image->move(public_path('dashboard/images/menu-img'), $image_name);
            $menu->image = $image_name;

        }

        $menu->save();
        return redirect()->route('menu_add')->with('success',' Menu Added Successfully ');
        
    }

    public function orderlist( Request $req)
    {
        #echo "inn";die();
        $login_user = Auth::user()->role;
        $loginId = Auth::user()->id;
        $current_date = "2025-02-12";
        $start_date = $req->input('start_date',$current_date);
        $end_date =   $req->input('end_date',$current_date);
        $userId =   $req->input('user_id',$loginId);
        
        if($login_user === 'admin'){
            
        }else{
            
        }

        $orderList = Order::with(['Order_items:id as item_id,order_id,menu_id,price,quantity,total'])
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where('u_id', '=', $userId)
            ->paginate(30);

        #dd($orderList->toArray());

        $userList = DB::table('users')->where('role','staff')->select('name','id')->get();

        #echo "<pre>";print_r($orderList);die();

        return view('dashboard.orderList',[

            'data'=>$orderList,
            'userList'=>$userList,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'userId' => $userId
        
        ]);

    } 

    public function bill_details(){

        echo "inn";
    }
    
}
