<?php
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

use App\Jobs\SendOTPMailJob;

use App\Jobs\BlockUserJob;
use App\Jobs\UnBlockUserJob;

use App\Jobs\SendNotificationJob;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use App\Jobs\UserAccountReActivateJob;

use App\Models\SiteSetting;
use App\Models\Notification;

use App\Models\ReportTag;
use App\Models\User;

// use Image;

function pr($array){
    print_r($array);
    exit;
}

// for Android and IOS
function sendNotification($notificationData, $android_token, $ios_token){
    SendNotificationJob::dispatch($notificationData, $android_token, $ios_token);
}

// random password generater
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// currency format 
function currency_format($amt){
    return number_format( $amt , 2 , '.' , ',' );
}

// common validation message for all api request param
function validationErrorMessage($validator){
    $error = (json_decode($validator->errors()));
    // $message = [];
    // foreach ($error as $key => $obj) {
    //     $message[$key] = $obj[0];
    // }
    $message = '';
    foreach ($error as $key => $obj) {
        $message .= $obj[0]."\n";
    }
    return  trim(str_replace('\n', '', (str_replace('\r', '', $message))));;
}

// common search for all datatable
function _commonSearchDatatable($searchQuery, $columnName_arr, $searchValue){
    foreach($columnName_arr as $columnKey => $columnValue){
        if(!empty($columnValue) && $columnValue['searchable'] == "true"){
            if (0) {
                $searchQuery = $searchQuery->where($columnValue['name'],'Like',"%".$searchValue."%");
            }else{
                $searchQuery = $searchQuery->orWhere($columnValue['name'],'Like',"%".$searchValue."%");
            }
        }
    }

    return $searchQuery;
}

//Random String Generate function
function generateRandomString($length = 36) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


//  Send OTP Mail
function sendOTPEmail($user)
{
    SendOTPMailJob::dispatch($user);
}

// Block User Mail
function blockUser($user)
{
    BlockUserJob::dispatch($user);
}

// Un Block User Mail
function unBlockUser($user)
{
    UnBlockUserJob::dispatch($user);
}

// Facebook Data Deletion
function parse_signed_request($signed_request) {
    list($encoded_sig, $payload) = explode('.', $signed_request, 2);

    $secret = env('FACEBOOK_CLIENT_SECRET'); // Use your app secret here

    // decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);
    
    $facebookLog = new Logger('facebook_deletion');
    $facebookLog->pushHandler(new StreamHandler(storage_path('logs/facebook_deletion'.Carbon::now()->format('y_m_d').'.log')), Logger::INFO);
    $facebookLog->info('MessageLog', ['data'=> $data, 'signed_request'=> $signed_request]);

    // confirm the signature
    $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
    if ($sig !== $expected_sig) {
        error_log('Bad Signed JSON signature!');
        return null;
    }

    return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}

// reactivated user account mail
function userAccountReactivated($user){
    UserAccountReActivateJob::dispatch($user);
}

// check user app version for android or ios
function checkUserAppVersion($platform, $appversion){
    switch (strtolower($platform)) {
        case 'ios':
            if(Config::get('constants.USER_IOS_APP_VERSION') > $appversion){
                return true;
            }
            break;

        case 'android':
            if(Config::get('constants.USER_ANDROID_APP_VERSION') > $appversion){
                return true;
            }
            break;

        default:
            if(Config::get('constants.USER_DEFAULT_APP_VERSION') > $appversion){
                return true;
            }
            break;
    }
}

function removeEmptyValueFromArray($array)
{
    foreach ($array as $key => $value) {
        if (is_null($value) || $value == '') {
            unset($array[$key]);
        }
    }
    return $array;
}

function getReportTagById($id)
{
   $report_tag = ReportTag::findOrFail($id);
   return  $report_tag->name;
}


function getUserDetailById($id)
{
   $user = User::findOrFail($id);
   return  $user;
}

function getUserNameById($id)
{
   $user = User::findOrFail($id);
   return  $user->fullname();
}

function getFileExtention($url)
{
    $file_path = $url;
    $extension = pathinfo($file_path, PATHINFO_EXTENSION);
    return $extension;
} 

function getFileType($extension)
{
    if($extension == "mp4" || $extension == "mkv" || $extension == "wmv")
    {
        return "video";
    }

    if($extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "gif")
    {
        return "image";
    }
}

/**
 * Create a thumbnail of Image
*
* @param string $path path of thumbnail
* @param int $width  desired width of thumbnail
* @param int $height desired height of thumbnail
*/

// function createImageThumbnail($path, $width, $height)
// {
//     $img = Image::make($path)->resize($width, $height, function ($constraint) {
//     $constraint->aspectRatio();
//     });

//     $img->save($path);
// }


/**
 *  Create a thumbnail of Video
* 
* @param string $videoeUrl video url path with file and file extension
* @param string $storageUrl storage url path where we want to store thumbnail of video
* @param int $second frame second from video timelapse
* @param int $width width of thumbnail
* @param int $height height of thumbnail
* @param int $unique_id unique id for set naming string of thumbnail file
*/

// function createVideoThumbnail($videoUrl,$storageUrl,$second, $width, $height,$unique_id)
// {
   
//     $thumbnail_name = $unique_id.'_'.time().'.png';

//       /* 
//         -i = Inputfile name
//         -vframes 1 = Output one frame
//         -an = Disable audio
//         -s 400x222 = Output size
//         -ss 30 = Grab the frame from 30 seconds into the video
//      */

//     $command = 'ffmpeg -i '.$videoUrl.' -s '.$width.'x'.$height.' -ss 00:00:'.$second.'.000 -vframes 1 '.$storageUrl.$thumbnail_name;
//     $result = exec($command);
//     if(!$result)
//     {
//         return false;
//     }
//     return true;
// }
