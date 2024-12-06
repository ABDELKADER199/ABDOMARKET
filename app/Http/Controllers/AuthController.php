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
            'face_descriptor' => 'required|json',
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
            'face_descriptor' => $data['face_descriptor'],
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
            return response([
                "message" => "Please Try Agien",
            ], 401);
        } else {
            $token = $user->createToken('myToken')->plainTextToken;

            return response([
                "message" => "Login Successfully",
                "User" => $user,
                "token" => $token
            ], 200);
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
    public function getCurrentUser(Request $request)
    {
        $user = User::where('email', $request->email)->first(); // مثال: جلب المستخدم حسب البريد الإلكتروني
        return response()->json($user);
    }


    public function loginWithFace()
    {
        $data = $request->validate([
            'face_descriptor' => 'required|json'
        ]);

        $inputDescriptor = json_decode($data['face_descriptor'], true);

        $users = User::all();

        foreach ($users as $user) {
            $storedDescriptor = json_decode($user->face_descriptor, true);
            $distance = $this->calculateEuclideanDistance($inputDescriptor, $storedDescriptor);

            if ($distance < 0.6) {

                $token = $user->createToken('myToken')->plainTextToken;
                return response([
                    "message" => "Login Successfully",
                    "User" => $user,
                    "token" => $token
                ], 200);
            }
        }

        return response([
            "message" =>  "Face not recognized"
        ], 401);
    }

    private function calculateEuclideanDistance($desc1, $desc2)
    {
        $sum = 0;

        for ($i = 0; $i < count($desc1); $i++) {
            $sum += pow($desc1[$i] - $desc2[$i], 2);
        }

        return sqrt($sum);
    }
}
