<?php

namespace App\Http\Controllers;

use App\Http\Requests\HelloRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Illuminate\Support\Facades\DB;

use function Termwind\style;

class HelloController extends Controller
{
    public function index(Request $request)
    {
        $items=db::table('people')->orderBy('age','asc')->get();
        return view('hello.index',['items'=>$items]);
    }

    public function post(Request $request)
    {
        $items = db::select('select * from people');
        return view('hello.index',['items'=>$items]);
    }

    public function add(Request $request)
    {
        return view('hello.add');
    }

    public function create(Request $request)
    {
        $param = [
            'name' => $request->name,
            'mail' => $request->mail,
            'age' => $request->age
        ];
        db::table('people')->insert($param);
        return redirect('/hello');
    }

    public function edit(Request $request)
    {
        $item = db::table('people')
        ->where('id',$request->id)->first();
        return view('hello.edit',['form'=>$item]);
    }

    public function update(Request $request)
    {
        $param = [
            'id' => $request->id,
            'name' => $request->name,
            'mail' => $request->mail,
            'age' => $request->age
        ];
        db::table('people')->where('id',$request->id)
        ->update($param);
        return redirect('/hello');
    }

    public function del(Request $request)
    {
        $item = db::table('people')->where('id',$request->id)->first();
        return view('hello.del',['form'=>$item]);
    }

    public function remove(Request $request)
    {
        db::table('people')->where('id',$request->id)->delete();
        return redirect('/hello');
    }

    public function show(Request $request)
    {
        $page = $request->page;
        $items = db::table('people')
        ->offset($page*3)
        ->limit(3)
        ->get();
        return view('hello.show',['items'=>$items]);
    }

}