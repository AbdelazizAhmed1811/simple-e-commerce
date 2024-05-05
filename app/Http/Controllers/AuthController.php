<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserCollection;
use Mail;
use Illuminate\Auth\Notifications\VerifyEmail;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
            ]);
            // Mail::raw('Text to e-mail', function ($message) {
            //     //
            // });

            // $user->notify(new VerifyEmail);

            DB::commit();



            return response()->json([
                'message' => 'User registered successfully. Please check your email for verification.'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($validated)) {
                return response()->json([
                    'message' => 'Login information invalid'
                ], 401);
            }

            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json(['user' => $user])
                ->header('Authorization', 'Bearer ' . $token);

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            Auth::user()->tokens()->delete();

            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function resetPassword(Request $request)
    {

        try {
            $validated = $request->validate([
                'password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ]);

            $user = Auth::user();
            $user->password = Hash::make($validated['password']);
            $user->save();

            return response()->json(['message' => 'Password reset successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 500);
        }
    }



    public function index()
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }

            $users = User::where('role', '!=', 'admin')->get();
            return new UserCollection($users);


        } catch (\Throwable $th) {

            return response()->json(["message" => $th->getMessage()], 500);
        }
    }
}




