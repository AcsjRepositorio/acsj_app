<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $users=User::all();  
          $userCount=$users->count();    

          return view('adminpanel.manage_users', compact('users','userCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


    return view('adminpanel.create_users');
        
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated=$request->validate([

            'name' => 'required|string|max:225',
            'email' => 'required|string|email|max:225|unique:users,email',
            'password' => 'required|string|min:8|confirmed', 
            'user_type'=>'required|integer|in:1,2,3',
            
            

        ]);


      

        User::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'password'=>$validated['password'],
            'user_type'=>$validated['user_type'],
            

        ]);
       

        return redirect('/adminpanel/manage_users')->with('success', 'Usuário salvo  com sucesso!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user= User::findOrfail($id);
        return view('adminpanel.edit_users',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_type'=>'required|integer|in:1,2,3'
        ]);

        $user=User::findOrfail($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->user_type=$request->user_type;
        
        if($request->password){
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/adminpanel/manage_users')->with('success', 'Usuário editado  com sucesso!');

    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(string $id)
        {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect('/adminpanel/manage_users')->with('success', 'Usuário excluído com sucesso!');
        }
}
