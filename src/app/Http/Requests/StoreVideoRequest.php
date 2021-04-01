<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\BouncerFacade as Bouncer;

class StoreVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'video' => 'required|file|max:200000|mimes:mp4,mpeg,mkv',
        ];
    }

    public function messages()
    {
        return [
            'video.required' => 'A video file is required.',
            'video.max' => 'A max file size of 200mb is allowed.',
            'video.mimes' => 'Only mp4, mpeg, and mkv are allowed.'
        ];
    }
}
