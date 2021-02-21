<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Exception;
use Illuminate\Http\Request;

class VideoController extends Controller
{
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
    public function store(Request $request)
    {
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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