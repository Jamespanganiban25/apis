<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Services\CookieService;


class UserController extends Controller 
{
public $successStatus = 200;
public $createSuccess = 201;
/** 


     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    protected $maxAttempts = 5; // Default is 5
    protected $decayMinutes = 2; // Default is 1

    public function login(){
        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];
        $loginAttemtps = CookieService::getCookie('loginAttempts') ?? 0;

        if($loginAttemtps > $this->maxAttempts) {
            $response = [ 'message' => 'Max Login Attempt', $loginAttemtps];
            return response()->json($response, 401);   
        }
        if(Auth::attempt($credentials)) {

            $user = Auth::user(); 
            $success['access_token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json($success, $this-> createSuccess); 
        } else { 
             CookieService::setCookie('loginAttempts', ++$loginAttemtps, 5);
            return response()->json([
                'message'=>'Invalid credentials'
        ], 401); 
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
            'c_password' => 'required|same:password', 
        ]);

        // if(User::whereEmail('email', $request->email)->count()) {

//         dd(User::where('email', $request->email)->count(),
// User::where('email', $request->email)->toSql()
//     );

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
        // $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        // $success['name'] =  $user->name;
        //$success['message'] = 'User successfully Registered';
// return response()->json(['success'=>$success], $this-> createSuccess); 
     /*   [
            'success' => [
                'token' => '',
                'name' => ''
            ]
        ] ctrl + / */ 

        $success['message'] = 'User successfully Registered';
        return response()->json($success, $this-> createSuccess); 
        // [
        //     'success' => 'User successfully Registered'
        // ]
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
}