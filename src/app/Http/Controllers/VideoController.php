<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function upload()
    {
        $request = request();

        if ($request->has('video')) {
            try {
                // Generate UUID for video
                $uuid = Str::uuid()->toString();

                $uploadedVideo = $request->file('video');

                // Save video to storage/videos
                $uploadedVideo->storeAs(
                    'videos',
                    $uuid . '.' . $uploadedVideo->getClientOriginalExtension()
                );

                return response()->json([
                    'msg' => 'Video uploaded successfully',
                    'uuid' => $uuid,
                    'name' => basename($uploadedVideo->getClientOriginalName(), '.' . $uploadedVideo->getClientOriginalExtension()),
                ]);
            } catch (Exception $exception) {
                return response()->json([], 404);
            }
        } else {
            return response()->json([], 404);
        }
    }
}