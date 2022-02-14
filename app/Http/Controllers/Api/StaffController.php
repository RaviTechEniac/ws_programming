<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Events\SocketEvent;
use Illuminate\Support\Facades\DB;
use App\Models\SiteSettings;
use Validator;
use Config;


class StaffController extends Controller
{
    public $successStatus = 'Successful';
    public $failStatus = 'Failed';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $user = auth()->user();

        $validate = [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'age' => 'required',
            'email' => 'email|required|unique:users,email',
            'designation' => 'required'
        ];

        $validator = Validator::make($request->all(), $validate);

        if($validator->fails()){

            $message = $validator->messages();
            return response()->json(['success' => false, 'code'=>$this->failStatus, 'message'=>$message],500);
        }
        
        $inputs = $request->all();
        $new_employee =  Staff::create($inputs);
        $message = 'Staff added';
        

        if(SiteSettings::where('id',1)->where('socket_connection','open')->exists())
        {
            $response =  response()->json(['success' => true,'data' => $new_employee, 'code'=>$this->successStatus, 'message'=>$message]);
            event(new SocketEvent($response));
        } 
        else
        {
            return response()->json(['success' => true,'data' => $new_employee, 'code'=>$this->successStatus, 'message'=>$message]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff ,Request $request)
    {   
        $user = auth()->user();
        
        $message = 'staff retrived';
      
        if(DB::table('site_settings')->where('id',1)->where('socket_connection','open')->exists())
        {
            $response = response()->json(['success' => true,'data' => $staff->all(), 'code'=>$this->successStatus, 'message'=>$message]);
            event(new SocketEvent($response)); 
        }
        else
        {   
            return response()->json(['success' => true,'data' =>$staff->all(), 'code'=>$this->successStatus, 'message'=>$message]);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staff $staff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        //
    }
}
