<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $hasItems = Person::has('board')->get();
        $noItems= Person::doesntHave('board')->get();
        $param = ['hasItem' => $hasItems,'noItems' => $noItems];
        return view('person.index',$param);
    }

    public function find(Request $request)
    {
        return view('person.find',['input'=>'']);
    }

    public function search(Request $request)
    {
        $min=$request->input*1;
        $max=$min+10;
        $item=Person::ageGreaterThan($min)->ageLessThan($max)->first();
        $param = ['input'=>$request->input,'item'=>$item];
        return view('person.find',$param);
    }

    public function add(Request $request)
    {
        return view('person.add');
    }

    public function create(Request $request)
    {
        $this->validate($request,Person::$rules);
        //peopleモデル取得
        $person = new Person;
        //リクエストで送られてきたコンテンツを全聚徳
        $form = $request->all();
        //tokenはレコードに追加しないので消す
        unset($form['_token']);
        //personレコードにフォームから送られてきた内容を保存
        $person->fill($form)->save();
        return redirect('/person');
    }

    public function edit(Request $request)
    {
        $person = Person::find($request->id);
        return view('person.edit',['form'=>$person]);
    }

    public function update(Request $request)
    {
        $this->validate($request,Person::$rules);
        $person = Person::find($request->id);
        $form = $request->all();
        unset($form['_token']);
        $person->fill($form)->save();
        return redirect('/person');
    }

    public function del(Request $request)
    {
        $person = Person::find($request->id);
        return view('person.del',['form'=>$person]);
    }

    public function remove(Request $request)
    {
        $person = Person::find($request->id);
        $person->delete();
        return redirect('/person');
    }
}
