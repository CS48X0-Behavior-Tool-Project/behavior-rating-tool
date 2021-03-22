<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Jobs\ConvertVideoForStreaming;
use App\Models\Video;
use Bouncer;
use Carbon\Carbon;
use Exception;
use FFMpeg\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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
    public function store(StoreVideoRequest $request)
    {
        if ($request->user()->can('create', Video::class)) {
            if ($request->has('video')) {
                try {
                    $video = Video::create([
                        'disk' => 'video_storage',
                        'original_name' => $request->file('video')->getClientOriginalName(),
                        'path' => $request->file('video')->store('temp', 'video_storage'),
                        'name' => $request->file('video')->getClientOriginalName(),
                        'created_at' => Carbon::now(),
                    ]);

                    // Dispatch a queue event to update video size and filetype
                    $this->dispatch(new ConvertVideoForStreaming($video));

                    return response()->json([
                        'msg' => 'Video uploaded successfully',
                        'uuid' => $video->id,
                        'name' => $video->name,
                    ]);
                } catch (Exception $exception) {
                    abort(500, 'Failed to process video');
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
            $video = Video::find($id);
            $videoFile = Storage::disk('video_storage')->get($video->path);
            $response = FacadeResponse::make($videoFile, 200);
            $response->header('Content-Type', 'video/mp4');
            return $response;
        } else {
            abort(403);
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
