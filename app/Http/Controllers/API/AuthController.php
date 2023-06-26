<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mail;
use App\Mail\DemoMail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request){
        // validation
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User register successfully'
        ];

        return response()->json($response,200);

    }

    public function login(Request $request){
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login successfully'
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'success' => false,
                'message' => 'Unauthorised'
            ];
            return response()->json($response);
        }
    }

    //-----------------------------------------------------------------------------

    public function forgot(Request $request){
        // validation
        $validator = Validator::make($request->all(),[
           'email' => 'required|email',
          ]);
      
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' =>  $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }
        $count=  User::where('email',$request->email)->count();
        if($count==0){
            $response = [
                'success' => false,
                'message' => "Emi not Exist"
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $otp=mt_rand(100000, 999999);
        DB::table('password_resets')->where('email',$request->email)->delete();
         DB::table('password_resets')->insert(array(
            'email'=>$input['email'],
            'token'=>md5($otp),
            'created_at'=>Carbon::now()->addMinute(3)
        ));
        $success['token'] = $otp;
        $subject="Reset Password";
        $msg="you have been reset for reset password you otp for password reset ".$otp;
      $this->  send_mail($subject,$request->email,$msg);
      
       // $currentDateTime = Carbon::now();
      //  $newDateTime = Carbon::now()->addMinute(3);
        
        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User register successfully'
        ];

        return response()->json($response,200);

    }
    //----------------------------------------------------------------------
    
    public function verify(Request $request){
        // validation
        $validator = Validator::make($request->all(),[
           'otp' => 'required',
          ]);
      
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => "Otp Has requried"
            ];
            return response()->json($response, 400);
        }
       
        $otp=$request->otp;
      $user= DB::table('password_resets')->where('token',md5($otp))->get();
        if(count( $user)==0){
            $response = [
                'success' => false,
                'message' => "Otp Not Found"
            ];
            return response()->json($response, 400);
        }


       
        $count = DB::table('password_resets')->where('created_at', '>', now())->where('token',$otp)->count();
        if($count==0){
            $response = [
                'success' => false,
                'message' => "Otp Has Been Expired"
            ];
            return response()->json($response, 400);
        }
     


     $success['token'] =$user[0]->token;
            $success['email'] = $user[0]->email;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login successfully'
            ];
        
      
        return response()->json($response,200);

    }
    //------------------------------------------------------------------------------


    public function showreset(Request $request){
        // validation
        $validator = Validator::make($request->all(),[
           'email' => 'required',
           'token'=> 'required',
          ]);
      
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' =>  $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }
       
        $otp=$request->token;
  


       
        $count = DB::table('password_resets')->where('email',$request->email)->where('token',$otp)->count();
        if($count==0){
            DB::table('password_resets')->where('email',$request->email)->delete();
            $response = [
                'success' => false,
                'message' => "Otp Has Been Expired"
            ];
            return response()->json($response, 400);
        }
    


     $success['token'] =$otp;
            $success['email'] = $request->email;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'Reeset your password'
            ];
        
      
        return response()->json($response,200);

    }
    //---------------------------------------------------------------------------------
    public function reset(Request $request){
        $validator = Validator::make($request->all(),[
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' =>  $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }
        
        $count = DB::table('password_resets')->where('email',$request->email)->where('token',$request->token)->count();
        if($count==0){
            $response = [
                'success' => false,
                'data'=>null,
                'message' => "Otp Has Been Expired"
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::where('email',$request->email)->update(array(
            'password'=>$input['password']
        ));

        DB::table('password_resets')->where('email',$request->email)->delete();







        $response = [
            'success' => true,
            'data' => $input,
            'message' => 'your password has been update'
        ];
        return response()->json($response,200);
    }

    //-------------------------------------------------------------------------------

    function send_mail($subject,$email,$msg){
     try {
        $mailData = [ 'title' => $subject, 'body' => $msg ];
        Mail::to($email)->send(new DemoMail($mailData));
    } catch (Exception $e) {
       throw new Exception($e);
    }
}
}
