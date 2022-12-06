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
use Session;
use Carbon\Carbon;
use DateTimeZone;
use DateTime;
use App\Mail\EmailAllocateDids; 
use App\Mail\EmailDeleteDid;
use Illuminate\Support\Facades\DB;
use Response;
use File;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
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
        return view('dashboard.employee', compact('current_user','employees'));
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
        Employee::create($data);
        return back()->with('success', " Successfully created")->withInput($request->all());                 	
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
        Employee::whereId($request->id)->update($data);
        return back()->with('success', " Successfully updated")->withInput($request->all());                 	
    }

    public function delete_employee(Request $request){

        $employee = Employee::find($request->id);
        $employee->delete();
        return back()->with('success', " Successfully deleted")->withInput($request->all());     
    }

    public function category(Request $request)
    {
                
        $current_user = auth()->user();
        $jobwall_categorys = Category::where('job_type','jobwall')->get();
        $jobdrawer_categorys = Category::where('job_type','jobdrawer')->get();
        return view('dashboard.category', compact('current_user','jobdrawer_categorys','jobwall_categorys'));
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
        Category::create($data);
        return back()->with('success', " Successfully created")->withInput($request->all());                 	
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
        Category::whereId($request->id)->update($data);
        return back()->with('success', " Successfully updated")->withInput($request->all());                 	
    }

    public function delete_category(Request $request){

        $category = Category::find($request->id);
        $category->delete();
        return back()->with('success', " Successfully deleted")->withInput($request->all());     
    }

    public function jobchat(Request $request){
        $current_user = auth()->user();
        $employees = EMployee::where("admin_id", $current_user->id)->get();
        $jobwall_categorys = Category::where('job_type','jobwall')->get();
        $jobdrawer_categorys = Category::where('job_type','jobdrawer')->get();
        $message_headers = Message_header::where('creator_id', $current_user->id)->where('owner_type','admin')->get();
        return view('dashboard.jobchat', compact('current_user','jobwall_categorys','jobdrawer_categorys','employees', 'message_headers'));
    }

    public function store_message_header(Request $request){
        $current_user = auth()->user();

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'object' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'employee' => ['required'],  
            'category' => ['required'],           
        ]);

        $employees = $request->employee;
        foreach ($employees as $i => $employee) {
            $data = [
                            'title'              => $request->title,
                            'object'             => $request->object,
                            'category_id'           => $request->category,
                            'creator_id'            => $current_user->id,
                            'receiver_id'       => $employee,     
                            'owner_type'         => $request->owner_type                   
            ]; 
            $category = Category::where('id',$request->category)->first();
            $employee_name = Employee::find($employee)->name;
            $message_header = Message_header::create($data);

            if($message_header){
                $data_message = [
                    'content'  =>  $request->message,
                    'header_id' => $message_header->id,
                    'creator_id' => $current_user->id,
                    'owner_type'         => $request->owner_type,
                    'is_read'   => 0
                ];
                Message::create($data_message);
                if($i == 0){
                    if($request->hasFile('attached'))
                    {
                        $allowedfileExtension=['pdf','jpg','png','docx'];
                        $files = $request->file('attached');
                        foreach($files as $key=>$file){

                    // File Details 
                            $no = $key+1;
                            $filename = $file->getClientOriginalName();
                            $filenames[$key] = $filename;
                            $extension = $file->getClientOriginalExtension();
                            $extensions[$key] = $extension;
                            $tempPath = $file->getRealPath();
                            $fileSize = $file->getSize();
                            $mimeType = $file->getMimeType();

                            // Valid File Extensions
                            $valid_extension = array("pdf","docx");

                            // 2MB in Bytes
                            $maxFileSize = 10097152; 

                            // Check file extension
                            if(in_array(strtolower($extension),$valid_extension)){

                                // Check file size
                                if($fileSize <= $maxFileSize){

                                    if($category->job_type == "jobwall"){
                                        $path = 'uploads/jobwall/'.$category->name;
                                            if(!File::isDirectory(public_path($path))){

                                                File::makeDirectory($path, 0777, true, true);

                                            }
                                    }
                                    else{
                                        $path = 'uploads/jobdrawer/'.$category->name;
                                            if(!File::isDirectory(public_path($path))){

                                                File::makeDirectory($path, 0777, true, true);

                                            }                                    
                                    }

                                    $file_name = $employee_name.'_'.time().'_'.$filename;
                                    //remove image before upload

                                    $file_path = $file->move(public_path($path), $file_name);
                                    $file_paths[$key] = $file_path;
                                    $radio_name = 'attach_sign_'.$no;

                                    $data_message_attachment = [
                                        'name'   =>  $file_name,
                                        'path'   =>  $file_path,
                                        'header_id'   =>  $message_header->id,
                                        'is_sign' => $request->$radio_name == 'yes'? 1 : 0,
                                    ];
                                    Message_attachment::create($data_message_attachment);

                                }else{
                                    Session::flash('error','File too large. File must be less than 2MB.');
                                }
                            }else{
                                Session::flash('error','Invalid File Extension.');
                            }
                            }
                    }else{
                                Session::flash('error','upload file');
                    }
                }
                else{
                        foreach($file_paths as $key => $file_path){
                            $file_name = $employee_name.'_'.time().'_'.$filenames[$key];
                            $second_path = $path.'/'.$file_name;
                            Log::info($second_path);
                            if(copy($file_path, $second_path)){
                                $data_message_attachment = [
                                    'name'   =>  $file_name,
                                    'path'   =>  $second_path,
                                    'header_id'   =>  $message_header->id,
                                    'is_sign' => $request->$radio_name == 'yes'? 1 : 0,
                                ];
                                Message_attachment::create($data_message_attachment);
                            }
                        }
                }            
            }
        }

        return back()->with('success', " Successfully sent")->withInput($request->all()); 
    }

    public function message_list(Request $request, $id){
        $current_user = auth()->user();
        $message_header = Message_header::where('id', $id)->first();
        $messages = Message::where('header_id',$id)->get();
        $message_attachments = Message_attachment::where('header_id', $id)->get();
        return view('dashboard.message-list', compact('current_user','message_header','messages', 'message_attachments'));        

    }

    public function store_message(Request $request){
        $request->validate([
            'message' => ['required', 'string'],         
        ]);
        $current_user = auth()->user();
                    $data = [
                        'content'              => $request->message,  
                        'owner_type'         => $request->owner_type,
                        'header_id'          => $request->header_id,
                        'creator_id'           => $current_user->id,     
                        'is_read'   => 0             
                    ];      
        Message::create($data);               
        return back()->with('success', " Successfully sent")->withInput($request->all()); 
    }

    public function generation_usercode(Request $request){
        $current_user = auth()->user();
        return view('dashboard.generation-usercode', compact('current_user'));  
    }

    public function generate_usercode(Request $request){

    }
}    