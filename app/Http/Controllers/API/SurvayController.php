<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Generate;
use App\Models\Survay;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mail;
use App\Mail\DemoMail;
use Illuminate\Support\Facades\DB;
class SurvayController extends Controller
{
    //
//-----------------------------------------------------------------------------
 public function newlink(Request $request){
            // validation
$validator = Validator::make($request->all(),[  'email' => 'required|email',  'expire_date'=>'required'   ]);
if($validator->fails()){ $response = [
                    'success' => false,
                    'message' =>  $validator->errors()->first()
                ];
                return response()->json($response, 400);
            }

            $count=  Generate::where('email',$request->email)->count();
            if($count>0){
                $response = [
                    'success' => false,
                    'message' => "Link Has Already Been Generated for this Email"
                ];
                return response()->json($response, 400);
            }
    
            $input = $request->all();
            $otp=mt_rand(100000, 999999);
            $otp=md5($otp);
           Generate::insert(array(
                'email'=>$input['email'],
                'token'=>$otp,
                'expire_date'=>$request->expire_date,
                'created_at'=>Carbon::now()
            ));
            $success['token'] = $otp;
            $url=url('/survay');
            $url=$url."?token=".$otp;
            $subject="Survay Links Has ";
            $msg='Please <a target="_blank" href="'.$url.' " >click here </a> to submit your survay';
         // $this->  send_mail($subject,$request->email,$msg);
         $mailData = [ 'title' => $subject, 'body' => $msg ];
        Mail::to($input['email'])->send(new DemoMail($mailData));
           // $currentDateTime = Carbon::now();
          //  $newDateTime = Carbon::now()->addMinute(3);
            
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'links has been generated successfully'
            ];
    
            return response()->json($response,200);
    
        }
        //------------------------------------------------------------------------
        
        public function checksurvay(Request $request){
            // validation
            $validator = Validator::make($request->all(),[
               'token'=>'required'  
            ]);
          
            if($validator->fails()){
                $response = [
                    'success' => false,
                    'message' =>  $validator->errors()->first()
                ];
                return response()->json($response, 400);
            }

            $count=  Generate::where('token',$request->token) ->where('created_at', '>', now())->count();
            if($count==0){
                $response = [
                    'success' => false,
                    'message' => "invalid Links"
                ];
                return response()->json($response, 400);
            }
    
           // $currentDateTime = Carbon::now();
          //  $newDateTime = Carbon::now()->addMinute(3);
          $response = [
                'success' => true,
                'data' => $request->token,
                'message' => 'pleas submit your survay to fill the form'
            ];
    
            return response()->json($response,200);
    
        }

        //-------------------------------------------------------------------------

        
        //------------------------------------------------------------------------
        
        public function submitsurvay(Request $request){
            // validation
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:survays',
                'message' => 'required',
            ]);
          
            if($validator->fails()){
                $response = [
                    'success' => false,
                    'message' =>  $validator->errors()->first()
                ];
                return response()->json($response, 400);
            }
/*
            $count=  Generate::where('token',$request->token) ->count();
            if($count==0){
                $response = [
                    'success' => false,
                    'message' => "links has been expired"
                ];
                return response()->json($response, 400);
            }

*/
            Survay::insert(array(
                'email'=>$request->email,
                'name'=>$request->name,
                'message'=>$request->message,
                
            ));

           // $currentDateTime = Carbon::now();
          //  $newDateTime = Carbon::now()->addMinute(3);
            Generate::where('email',$request->token)->delete();
            $response = [
                'success' => true,
                'data' => $request->email,
                'message' => 'your message has been submitted'
            ];
    
            return response()->json($response,200);
    
        }
        //-------------------------------------------------------------------------------
        public function getsurvay(){
           $survay= Survay::orderby('id','desc')->get();
            $response = [
                'success' => true,
                'data' => $survay,
                'message' => 'list of survays'
            ];
            return response()->json($response,200);
        }
        //----------------------------------------------------------------
}
