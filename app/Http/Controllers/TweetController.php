<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
    /**
     * Display the latest tweets.
     *
     */
    public function index()
    {
        $following = request()
            ->user()
            ->following()
            ->with('tweets.user:id,message')
            ->get();

        return response()->json($following);
    }

    /**
     * Store a newly created tweet in storage.
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
            'attachments' => 'sometimes|array',
            'attachments.*' => 'file',
        ]);

        $tweet = $request->user()->tweets()->create($request->only('message'));

        /**
         * Handle attachments from a tweet.
         *
         * Documentation:
         * https://laravel.com/docs/10.x/filesystem#storing-files
         */
        if ($request->has('attachments')) {
            foreach ($request->attachments as $attachment) {
                $path = $attachment->store('attachments');
                $tweet->attachments()->create(compact('path'));
            }
        }

        return response()->noContent();
    }

    /**
     * Display a specific tweet.
     *
     */
    public function show(Tweet $tweet)
    {
        return response()->json($tweet->with('user:id,name', 'attachments:id,path'));
    }

    /**
     * Update the specified tweet in storage.
     *
     */
    public function update(Request $request, Tweet $tweet)
    {
        $this->authorize('update', $tweet);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $tweet->update($validated);

        return response()->noContent();
    }

    /**
     * Remove the specified tweet from storage.
     *
     */
    public function destroy(Tweet $tweet)
    {
        $this->authorize('delete', $tweet);

        /**
         * Delete all files attached to the tweet
         * before removing it from storage.
         *
         * Documentation:
         * https://laravel.com/docs/10.x/filesystem#deleting-files
         */
        foreach ($tweet->attachments as $attachment) {
            Storage::delete($attachment->path);
        }

        $tweet->delete();

        return response()->noContent();
    }
}
