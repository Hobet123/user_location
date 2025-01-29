<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BlueMail;

use App\Models\User;

use App\Models\Email;

use App\Models\UserLocation;

use Illuminate\Support\Facades\View;

class HelperController extends Controller
{

    public static function getUserIP() {

        $proxy = "YES"; 

        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } 
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
        // HTTP_X_FORWARDED_FOR can contain multiple IP addresses, the first one being the client's IP
            
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ipList[0]);
        }
         elseif (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } 
        elseif (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        //HTTP_X_CLUSTER_CLIENT_IP: IP address through a load balancer.
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } 
        elseif (isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } 
        elseif (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED'])) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } 
        elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {

            $proxy = "NO";

            $ip = $_SERVER['REMOTE_ADDR'];

        } 
        else {
            $ip = 'UNKNOWN';
        }

        $result= [];

        $result['proxy'] = $proxy;
        $result['ip'] = $ip;

        return $result['ip'];
    }

    public static function getIPdetails($server_ip){

        // echo $server_ip;

        $ch = curl_init();

        // Set the URL that you want to GET by using the CURLOPT_URL option.
        curl_setopt($ch, CURLOPT_URL, env('IP_GEO').'&ip_address='.$server_ip); // toronto

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($data);
        
        return $data;

    }

}