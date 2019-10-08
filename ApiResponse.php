<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 */

namespace App\Library;

trait ApiResponse
{

    public function generateMsgError(int $statusCode)
    {
        switch ($statusCode){
            case 401:
                    $msg = 'Unauthorized';
                break;
            case 404:
                    $msg = 'Page Not Found';
                break;
            case 403:
                    $msg = 'Forbidden';
                break;
            case 405:
                    $msg = 'Method Not Allowed';
                break;
            default:
                    $msg = 'Whoops, looks like something went wrong';
                break;
        }

        return $msg;
    }


    public function api($data = [], $statsCode = 200, $msg = null)
    {
        $response['response'] = $data;
        $response['msg'] = $msg;

        return response()->json($response, $statsCode);
    }


    public function apiErrors($errors = [], $statsCode = 400, $msg = null)
    {
        $response['errors'] = $errors;
        $response['msg'] = $msg;

        return response()->json($response, $statsCode);
    }


}