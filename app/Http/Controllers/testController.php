<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class TestController extends Controller
{
    public function index(){
        $items = DB::select('select * from people');
        return view('test',['items' => $items]);
    }
 
}