<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Message;
use App\Models\Message_attachment;
use App\Models\Message_header;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use App\Models\buy_rates_australia;
use App\Models\buy_rates_newzealand;
use Session;
use Carbon\Carbon;
use DateTimeZone;
use DateTime;
use App\Mail\EmailAllocateDids; 
use App\Mail\EmailDeleteDid;
use Illuminate\Support\Facades\DB;
use Response;


class ApiController extends Controller
{

    public function index(Request $request)
    {
        $current_user = auth()->user();
        return view('dashboard.index', compact('current_user'));
    }

    public function employee(Request $request)
    {
                
        $current_user = auth()->user();
        $employees = Employee::get();
        return response()->json([
              'data' => $employees
             ], 200);
    }   

    public function store_employee(Request $request){
        $current_user = auth()->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],            
        ]);

                    $data = [
                        "admin_id"          => $current_user->id,
                        'name'              => $request->name,
                        'surname'              => $request->surname,
                        'email'              => $request->email,
                        'phone'           => $request->phone,
                    ];
        $employee = Employee::create($data);
        if ($employee) {
              return response()->json([
                        'data' => [
                            'type' => 'employee',
                            'message' => 'Success',
                            'employee' => $employee
                        ]
                    ], 201);
             } else {
              return response()->json([
                        'type' => 'employee',
                        'message' => 'Fail'
                    ], 400);
             }               	
    } 

    public function update_employee(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],            
        ]);

                    $data = [
                        'name'              => $request->name,
                        'surname'              => $request->surname,
                        'email'              => $request->email,
                        'phone'           => $request->phone,
                    ];
        $employee = Employee::whereId($request->id)->update($data);
        if ($employee) {
              return response()->json([
                        'data' => [
                            'type' => 'employee',
                            'message' => 'Success',
                            'employee' => $employee
                        ]
                    ], 201);
             } else {
              return response()->json([
                        'type' => 'employee',
                        'message' => 'Fail'
                    ], 400);
             }                 	
    }

    public function delete_employee(Request $request){

        $employee = Employee::find($request->id);

        if ($employee) {
                $employee->delete();
                return response()->json(['data' => [
                            'type' => 'category',
                            'message' => 'Success',
                        ]
                    ], 204);
                } else {
                    return response()->json([
                        'type' => 'employee',
                        'message' => 'Not Found'
                    ], 404);
        }   
    }

    public function category(Request $request)
    {
                
        $current_user = auth()->user();
        $jobwall_categorys = Category::where('job_type','jobwall')->get();
        $jobdrawer_categorys = Category::where('job_type','jobdrawer')->get();
        return response()->json([
              'data' => [
                            'jobwall_categorys' => $jobwall_categorys,
                            'jobdrawer_categorys' => $jobdrawer_categorys
                        ]
             ], 200);
    }   

    public function store_category(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255'],           
        ]);

                    $data = [
                        'name'              => $request->name,
                        'job_type'              => $request->job_type,
                    ];
        $category = Category::create($data);
        if ($category) {
              return response()->json([
                        'data' => [
                            'type' => 'category',
                            'message' => 'Success',
                            'category' => $category
                        ]
                    ], 201);
             } else {
              return response()->json([
                        'type' => 'category',
                        'message' => 'Fail'
                    ], 400);
             }                  
    } 

    public function update_category(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:255'],         
        ]);

                    $data = [
                        'name'              => $request->name,
                        'job_type'              => $request->job_type,
                    ];
        $category = Category::whereId($request->id)->update($data);
        if ($category) {
              return response()->json([
                        'data' => [
                            'type' => 'category',
                            'message' => 'Success',
                            'category' => $category
                        ]
                    ], 201);
             } else {
              return response()->json([
                        'type' => 'category',
                        'message' => 'Fail'
                    ], 400);
             }                    
    }

    public function delete_category(Request $request){

        $category = Category::find($request->id);
        if ($category) {
                $category->delete();
                return response()->json(['data' => [
                            'type' => 'category',
                            'message' => 'Success',
                        ]
                    ], 204);
                } else {
                    return response()->json([
                        'type' => 'category',
                        'message' => 'Not Found'
                    ], 404);
        }    
    }    

    public function jobchat(Request $request){
        //all jobchats
        //
        $request_data = $request->all();
        $message_headers = Message_header::where('creator_id', $request_data['employee_id'])->where('owner_type','admin')->get();
        $jsondata = [];
        foreach($message_headers as $message_header){
            switch($request_data['jobtype']) {
                case Category::find($message_header->category_id)->job_type:
                    $jsondata []= array(
                        'employee_name'=>Employee::get_employee_name($message_header->receiver_id),
                        'title'=>$message_header->title,
                        'object'=>$message_header->object,
                        'msg_num'=>5,
                        'color'=>'red ',
                        'is_read'=>0,
                    );
                    break;
                case Category::find($message_header->category_id)->job_type :
                    $jsondata []= array(
                        'employee_name'=>Employee::get_employee_name($message_header->receiver_id),
                        'title'=>$message_header->title,
                        'object'=>$message_header->object,
                        'msg_num'=>5,
                        'color'=>'red ',
                        'is_read'=>0,
                    );
                    break;
                default :
                    $jsondata []= array(
                        'employee_name'=>Employee::get_employee_name($message_header->receiver_id),
                        'title'=>$message_header->title,
                        'object'=>$message_header->object,
                        'msg_num'=>5,
                        'color'=>'red ',
                        'is_read'=>0,
                    );
                    break;
            }
        }
       
        return response()->json([
            'data' => [
                          'message_headers' => $jsondata,
                      ]
           ], 200);
    }
}    