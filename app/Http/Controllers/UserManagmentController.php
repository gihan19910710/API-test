<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMobileNumber;
use App\Models\UserRegistrationLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserManagmentController extends Controller
{
    // create user logic according to createUser Api
    public function createUser(Request $request){

        // Get only the necessary inputs
        $fullName = $request->input('fullName');
        $emailAddress = $request->input('emailAddress');
        $password = $request->input('password');
        $confirmPassword = $request->input('confirmPassword');
        $action = "Create User";

        // Check if the user already exists by email
        $checkUser = User::where('email', '=', $emailAddress)->first();

        if ($checkUser) {
            return response()->json(['error' => 'User already exists'], 400);
        }

        // Check if the password and confirm password match
        if ($password !== $confirmPassword) {
            return response()->json(['error' => 'Passwords do not match'], 400);
        }

        // Hash the password
        $hashPassword = Hash::make($password);

        // Create a new user
        $insertUserDetails = new User();
        $insertUserDetails->name = $fullName;
        $insertUserDetails->email = $emailAddress;
        $insertUserDetails->password = $hashPassword;

        // Save the user and return response
        if ($insertUserDetails->save()) {
//            $createdUserID = $insertUserDetails->id;
//            $this->userLog($request, $createdUserID, $action, $createdUserID);

            return response()->json(['success' => 'User created successfully'], 201);
        }

        // If user creation fails
        return response()->json(['error' => 'Failed to create user'], 500);
    }


        public function getUserDetailsForDashboard(Request $request){

        $userId = $request->input('userId');
        $existingUser = User::find($userId);
        if ($existingUser) {
            return response()->json(['data'=>$existingUser],200);

        }else {
            return response()->json(['error' => 'User not found'], 400);
        }


        }


}
