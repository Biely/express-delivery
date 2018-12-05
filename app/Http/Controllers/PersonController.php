<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
     {
        $this->middleware('auth');
     } 

    public function index()
    {
        //$qtypes = collect(getQtypes());
        return view('person',['action' => 'index']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        return view('person',['action' => 'edit']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user,$person)
    {
        //
       $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'store' => ['required', 'string', 'max:255'],
            'etype' => ['required', 'string', 'max:255'],
            'qq' => ['required', 'string', 'max:255']
        ]);
        $data = $request->all();
        $user=$user->findOrFail($person);
        $user->name = $data['name'];
        $user->store = $data['store'];
        $user->etype = $data['etype'];
        $user->qq = $data['qq'];
        if($user->save()){
          return redirect()->route('person.index')->with('status', '修改成功。');
        }else{
          return back()->withErrors(['提交失败。']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
