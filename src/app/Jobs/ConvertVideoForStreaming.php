<?php

namespace App\Jobs;

use App\Models\Video;
use Carbon\Carbon;
use Exception;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->path)

            // We are updating the size of the video to 720p
            // This is a 16:9 resolution, should be updated for other resolutions
            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(720, 480));
            })
            ->export()

            // Saving to a new disk
            ->toDisk('video_storage')

            // We're setting the bitrate to 500kbps
            ->inFormat((new X264('aac', 'libx264'))->setKiloBitrate(500))
            ->save($this->video->id . '.mp4');

            $oldPath = $this->video->path;
            
            // Update the video with a converted_at timestamp
            $this->video->update([
                'path' => $this->video->id . '.mp4',
                'converted_at' => Carbon::now(),
            ]);
            
            // Delete the old video to save storage
            unlink(storage_path('videos/' . $oldPath));

        } catch (EncodingException $e) {
            print($e->getCommand() . "\n");
            print($e->getErrorOutput() . "\n");
        }
    }
}
