<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BlueMail;

// use App\Models\User;

// use App\Models\Email;

// use App\Models\Eloc;

use App\Models\MapAddress;

use Illuminate\Support\Facades\View;

use App\Http\Controllers\HelperController;

use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{

    public function __construct(){

        session_start();


    }

    public function index(){
        // $emails = Email::where('lastname', '!=', null)->limit(10)->get();
        return view('home');

    }

    public function map(){

        $server_ip = HelperController::getUserIP();

        $details = HelperController::getIPdetails($server_ip);

        // dd($details);

        return view('map_rev', ['details' => $details]);
    }
/*


*/
    public function saveMapAddress(Request $request){

        $server_ip = HelperController::getUserIP();

        $to      = $email = "paul.ph227@gmail.com";

        $subject = 'HR - visitor';

        $message = $fullHTML = "Visitor Address: ".$request->address;
        
        $feedback = ['message' => $message, 'subject' => $subject];

        $responce = BlueMail::generalEmail($to, $feedback);

        //
        // Create a new instance of MapAddress
        $newAddress = new MapAddress();
        $newAddress->lat = $request->lat;
        $newAddress->lng = $request->lng;
        $newAddress->address = $request->address;
        $newAddress->ip = $server_ip;
        // $newAddress->email = $request->email;

        // Save the new record to the database
        $newAddress->save();

        return $newAddress;
    }
/*

*/
    public static function email(){

        $email = "paul.ph227@gmail.com";

        $to      = $email;

        $subject = 'Job opportunity: Creative Content Editor (Remote)';
        
        $message = View::make('emails.email_job_01')->render();

        $message = str_replace("<%email%>", $to, $message);
        $message = str_replace("<%job_id%>", 1, $message);

        $htmlHeader = View::make('emails.header')->render();
        $htmlFooter = View::make('emails.footer')->render();
        // $htmlHeader = "";
        // $htmlFooter = "";

        $message = $fullHTML = $htmlHeader.$message.$htmlFooter;

        // $fullHTML = View::make('emails.table')->render();
        return $fullHTML;
        
        $feedback = ['message' => $message, 'subject' => $subject];

        $responce = BlueMail::generalEmail($to, $feedback);

        dd($responce);

        return view('home')->with('success', 'Email has beed sent!');
        //
    }
    public function aboutUs(){

        return view('about_us');

    }

    public function portfolio(){

        return view('portfolio');

    }

    public function quest(){

        return view('quest');

    }

    public function jobs(){

        return view('jobs');

    }

    public function contactUs(Request $request){

        return view('contact_form');

    }

    public function doContactUs(Request $request){


        $responce = doContactUs($request->email, $request->message);

        return view('contact_form')->with(['success' => 'Your form has been submitted!', 'form_status' => 1]);
        
    }

    public function geo(Request $request){

        if(!empty($request->ip)){
            $server_ip = $request->ip;
        }
        else{
            $server_ip = HelperController::getUserIP();
        }

        $server_ip = "99.213.88.82";

        $data = HelperController::getIPdetails($server_ip);


        $latitude = $data->latitude;
        $longitude = $data->longitude;

        $coded_email = "paul.ph227@gmail.com";

        return view('map5', compact('coded_email', 'latitude', 'longitude'));
        
    }

    public function track(Request $request){


        $email = $_GET['email'];

        $user_email = Email::where('email', $email)->first();

        // dd($user_email);

        if ($user_email) {

            //set session for email
            $_SESSION['user_email'] = $user_email->email;
            
            
            // indicate that email was opened and user went to page
            $user_email->active = 1;
            $user_email->save();

        }
        else{
            $user_email = null;
        }

    }


    public static function landedFromEmail($email){

        $user = Email::where('email', $email)->first();

        if ($user) {

            $user->active = 2;
            $user->save();

        }
        else{
            $user_email = null;
        }

        return true;


    }

    public function jobByEmail($job_id, $email){



        $user_server_ip = HelperController::getUserIP(); //get user ip
 
        $data = HelperController::getIPdetails($user_server_ip); // get ip info

        $status = self::landedFromEmail($email); // confirm email 
        /*
            record user location
        */
        $result = HelperController::insertUserLocation($email, $user_server_ip, $data->longitude, $data->latitude, $server_browser = 1);
        //*
        $longitude = $data->longitude;
        $latitude = $data->latitude;

        

        $job_body = View::make('jobs.job_01')->render();

        $coded_email = str_replace("@", "***", $email);

        return view('cur_job', compact('job_body', 'coded_email', 'latitude', 'longitude'));//job_body

    }

    public static function saveEmailLocation(Request $request){

        // dd();

        $email = str_replace("***", "@", $request->coded_email);

        $result = HelperController::insertUserLocation($email, NULL, $request->lon, $request->lat, $server_browser = 2);

        return $request;

    }



}
