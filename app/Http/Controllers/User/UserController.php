<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Returns the whole list of users from the db
        $users = User::all();

        return response()->json(['data' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the inputs
        $rules = [
          'name' => 'required',
          'email' => 'required|email|unique:users',
          'password' => 'required|min:6|confirmed'
        ];

        // Validate the Request
        $this->validate($request, $rules);

        $data = $request->all();

        // Encrypt the password
        $data['data'] = bcrypt($request->password);

        // Assign verified status for the user
        $data['verified'] = User::UNVERIFIED_USER;

        // Assign verification token
        $data['verification_token'] = User::generateVerificationCode();

        // All users are regulat users by default
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        // Return the filled user instance and 201 success
        return response()->json(['data' => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return instance of user if exists
        $user = User::findOrFail($id);

        return response()->json(['data' => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Obtain user instance
        $user = User::findOrFail($id);

        $rules = [
          // Need to validate email against user/userid
          'email'    => 'email|unique:users,email,'.$user->id,
          'password' => 'min:6|confirmed',
          // Ensure role only has 1 of two possible values
          'admin'    => 'in:'.User::ADMIN_USER.','.User::REGULAR_USER,
        ];

        // Validate the Request
        $this->validate($request, $rules);

        // Verify we recived a name update it in the user instance
        if ($request->has('name')) {
          $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
          $user->verified = User::UNVERIFIED_USER;
          // Establish new validation token
          $user->verification_token = User::generateVerificationCode();
          $user->email = $request->email;
        }

        // IF the request has a password we need to Encrypt
        if ($request->has('password')) {
          $user->password = bcrypt($request->password);
        }

        // If the request has an admin
        if ($request->has('admin')) {
          if (!$user->isVerified()) {
            return response()->json(['error' => 'Only verified users can modify the admin field', 'code' => 409], 409);
          }

          $user->admin = $request->admin;
        }

        // Check for changes in the user
        if (!$user->isDirty()) {
          return response()->json(['error' => 'Updata failed - you need to specify a different value', 'code' => 422], 422);
        }

        // Save the changes
        $user->save();

        return response()->json(['data' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Ensure the user exists and delete
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['data' => $user], 200);
    }
}
