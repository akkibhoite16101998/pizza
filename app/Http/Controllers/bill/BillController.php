<?php

namespace App\Http\Controllers\bill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillController extends Controller
{
    //

    public function create_order(Request $req){

        #return $req;
        echo "<pre>";print_r($_POST);die();
    }
}
