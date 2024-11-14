<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
            'phone' => 'required',
            'image_profile' => 'required|image',
            'department_id' => 'required|integer'
        ]);

        if ($request->hasFile('image_profile')) {
            $image = $request->file('image_profile');
            $image_name = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('profiles'), $image_name);
            $data['image_profile'] = 'profiles/' . $image_name;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'],
            'image_profile' => $data['image_profile'],
            'department_id' => $data['department_id']
        ]);

        $token = $user->createToken('myToken')->plainTextToken;

        $response = [
            "message" => "Sign Up Successfully",
            "User" => $user,
            "token" => $token
        ];
        return response($response, 200);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', '=', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            $response = [
                "message" => "Please Try Agien",
            ];
        } else {
            $token = $user->createToken('myToken')->plainTextToken;

            $response = [
                "message" => "Login Successfully",
                "User" => $user,
                "token" => $token
            ];
            return response($response, 200);
        };
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        $response = [
            "message" => "LogOut Successfully",
        ];
        return response($response, 200);
    }
}
