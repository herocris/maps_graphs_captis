<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class ManageApiUserController extends Controller
{
    public function login(Request $request){

        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }
        //dd("sdf");
        //$user = Auth::user();
        //$accessToken = $user::with('roles')->get();

        // if ($userRole) {
        //     $this->scope = $userRole->role;
        // }

        
        $accessToken = auth()->user()->createToken('authTokene', ['primer-scope','segundo-scope'])->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
        //return response($accessToken);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logout exitoso.']);
    }

}
