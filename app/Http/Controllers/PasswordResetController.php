<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function showResetForm($token)
    {
        // Display the password reset form with the token
        return view('auth.passwordReset', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required'
        ]);

        // Find the token and email in the database
        $passwordReset = DB::table('password_resets')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['error' => 'Invalid or expired token'], 400);
        }

        // Find the user by email and update the password
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = bcrypt($request->password);
            $user->save();

            // Delete the token
            DB::table('password_resets')->where('email', $request->email)->delete();

            return response()->json(['message' => 'Password has been reset successfully'], 200);
        }

        return response()->json(['error' => 'User not found'], 404);
    }
}
