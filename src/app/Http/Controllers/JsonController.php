<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonController extends Controller
{
    public function downloadJsonTemplate() {
        $file = public_path()."/template_files/json_template.json";
        $headers = array('Content-Type: application/json',);
        return response()->download($file, 'template.json',$headers);
    }
}
