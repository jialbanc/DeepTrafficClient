<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class UploadController extends Controller
{
    public function upload(Request $request){
        $base64 = base64_encode(file_get_contents($request->file('image')));
        $response = Curl::to(config('services.deep.endpoint').'/prediccion')
            ->withData( array( 'imagen' => $base64 ) )
            ->asJson()
            ->post();
        /*$respuesta = [
            'casco' => $response->casco ? 1 : 0,
            'persona' => $response->persona ? 1 : 0,
        ];*/
        $respuesta = [
            'casco' => 0,
            'persona' => 1
        ];
        return json_encode($respuesta);
    }
}
