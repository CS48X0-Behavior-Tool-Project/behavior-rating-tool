<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Bouncer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
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
    public function store(Request $request)
    {
        if ($request->user()->can('create', Video::class)) {
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
                    abort(404);
                }
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()) {
            $video = Storage::disk('local')->get('videos/' . $id . '.mp4');
            $response = FacadeResponse::make($video, 200);
            $response->header('Content-Type', 'video/mp4');
            return $response;
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
