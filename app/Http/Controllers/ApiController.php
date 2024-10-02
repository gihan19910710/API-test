<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRoleDescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', '=', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                // Attempt to log the user in
                Auth::loginUsingId($user->id);

                // Delete existing tokens
                $user->tokens()->delete();

                // Create a new access token
                $accessToken = $user->createToken('authToken')->accessToken;

                // Return response in the required format
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => [
                        'access_token' => $accessToken,
                        'token_type' => 'Bearer',
                        'expires_in' => 3600 // you can adjust this value as needed
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid password',
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email',
            ], 200);
        }
    }


    public function forgetPassword(Request $request){
        $email          = $request->input('email');
        $user           = User::where('email','=',$email)->first();

        if ($user) {

            $token = Str::random(64);

          DB::table('password_resets')->insert([
              'email' => $request->email,
              'token' => $token,
              'created_at' => Carbon::now()
            ]);

          Mail::send('emails.forgetPassword', ['token' => $token, 'email' => $email], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset your Password');
          });
            return response()->json(['http_status' => 'error','validate'=>'success'],200);
        } else {
            return response()->json(['http_status' => 'error','validate'=>'invalid_email'],200);
        }
    }


    public function submitResetPasswordForm(Request $request)
    {

        $updatePassword = DB::table('password_resets')
                            ->where([
                              'email' => $request->email,
                              'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
          return response()->json(['http_status' => 'error','validate'=>'invalid_token'],200);
          //   return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

      //   return redirect('/login')->with('message', 'Your password has been changed!');
        return response()->json(['http_status' => 'error','validate'=>'success'],200);
    }


}
