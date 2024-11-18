<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        
        $keyword = $request->keyword;
        if($keyword !== null){
       
        $users = User::where('name', 'like', "%{$keyword}%")->orwhere('kana', 'like', "%{$keyword}%")->paginate(15);
        $total = $users->total();
        }
        else{
            $users = User::paginate(15);
            $total = $users->total();
        }
      
        return view('admin.users.index', compact('users', 'total','keyword'));
}
    

    public function show($id){
        
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }
}
