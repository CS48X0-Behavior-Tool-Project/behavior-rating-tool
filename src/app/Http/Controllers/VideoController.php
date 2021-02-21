<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function upload()
    {
        $request = request();

        if ($request->has('video')) {
            try {
                $uploadedVideo = $request->file('video');
                $clientfilename = basename($uploadedVideo->getClientOriginalName(), '.' . $uploadedVideo->getClientOriginalExtension());

                // Create database entry for the video
                $dbVideo = Video::create([
                    'name' => $clientfilename,
                    'created_at' => now(),
                ]);

                // Save video to storage/videos
                $uploadedVideo->storeAs(
                    'videos',
                    $dbVideo->id . '.' . $uploadedVideo->getClientOriginalExtension()
                );

                return response()->json([
                    'msg' => 'Video uploaded successfully',
                    'uuid' => $dbVideo->id,
                    'name' => $clientfilename,
                ]);
            } catch (Exception $exception) {
                return response()->json([], 404);
            }
        } else {
            return response()->json([], 404);
        }
    }
}