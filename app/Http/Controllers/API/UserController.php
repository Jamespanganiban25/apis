<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;



class UserController extends Controller 
{
public $successStatus = 200;
public $createSuccess = 201;
/** 

     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    public function login(){
        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];
      
        if(Auth::attempt($credentials)) {

            $user = Auth::user(); 
            $success['access_token'] =  $user->createToken('MyApp')-> accessToken; 
       return response()->json($success, $this-> createSuccess); 
        } 
        else{ 
            return response()->json(['message'=>'Invalid credentials'], 401); 
        } 


    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            //'confirm_password' => 'required|same:password', 
        ]);
        if(User::where('email', $request->email)->count()) {
        
            return response()->json([
                'message'=>'Email Already Taken'
            ], 400); 
        }

        if ($validator->fails()) { 
                    return response()->json(['error'=>$validator->errors()], 401);            
                }
        $input = $request->all(); 
                $input['password'] = bcrypt($input['password']); 
                $user = User::create($input); 

                $success['message'] = 'User successfully Registered';
                return response()->json($success, $this-> createSuccess); 

            }

    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
}