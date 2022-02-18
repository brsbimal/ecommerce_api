<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;

use App\Http\Resources\SuccessResource;
use App\Http\Resources\ErrorResource;

use App\Models\User;
use App\Models\Role;

use Auth;

class AuthController extends Controller
{
    public function createUser(CreateUserRequest $request)
    {
        try{
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $role = new Role;
            $role->user_id = $user->id;
            $role->role = 'customer';
            $role->save();
            $data = array(
                'status_code' => '200',
                'message' => 'Customer created successfully!!',
                'data' => $user
            ); 
        return new SuccessResource($data);
        }catch(\Throwable $th){
            $data = array(
                'status_code' => '400',
                'message' => $th->getMessage(),
                'data' => []
            );
        return new ErrorResource($data);
        }
    }

    public function loginUser(LoginRequest $request)
    {
        try{
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user = Auth::user();
                $token = $user->createToken(
                    'Auth-Token'
                );

                $data = array(
                    'status_code' => '200',
                    'message' => 'Login successfully!!',
                    'data' => $token
                ); 
            }
        return new SuccessResource($data);
        }catch(\Throwable $th){
            $data = array(
                'status_code' => '400',
                'message' => $th->getMessage(),
                'data' => []
            );
        return new ErrorResource($data);
        }
    }

    public function updateUser(CreateUserRequest $request)
    {
        try{
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $role = new Role;
            $role->user_id = $user->id;
            $role->role = 'customer';
            $role->save();
            $data = array(
                'status_code' => '200',
                'message' => 'Customer info updated successfully!!',
                'data' => $user
            );
            return new SuccessResource($data);
        }catch(\Throwable $th){
            $data = array(
                'status_code' => '400',
                'message' => $th->getMessage(),
                'data' => []
            );
        return new ErrorResource($data);
        }
    }

    public function deleteUser(Request $request)
    {
        try{
            $user = User::findOrFail($request->id);
            $role = Role::where('user_id',$user->id)->first();
            $role->delete();
            $user->delete();
            $data = array(
                'status_code' => '200',
                'message' => 'Customer deleted updated successfully!!',
                'data' => []
            );
            return new SuccessResource($data);
        }catch(\Throwable $th){
            $data = array(
                'status_code' => '400',
                'message' => $th->getMessage(),
                'data' => []
            );
        return new ErrorResource($data);
        }

    }

    public function getUsers()
    {

        return User::all();

    }
}
