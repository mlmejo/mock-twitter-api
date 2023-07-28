<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    /**
     * Get a list of suggested users to follow.
     *
     */
    public function index()
    {
        $following = request()
            ->user()
            ->following
            ->transform(function (User $user) {
                return $user->following;
            })
            ->collapse()
            ->unique('id');

        return response()->json($following);
    }

    /**
     * The authenticated user follows the specified user.
     */
    public function store(User $user)
    {
        /**
         * A user must be prevented from following themselves.
         *
         */
        if ($user->id === request()->user()->id) {
            return back()->withErrors([
                'user_id' => 'You cannot follow yourself.',
            ]);
        }

        $user->followers()->attach(request()->user()->id);

        return response()->noContent();
    }

    /**
     * Display the followers of a specified user.
     */
    public function show(User $user)
    {
        return response()->json(
            $user->followers
        );
    }

    /**
     * The authenticated user is removed from the specified user's
     * follower list.
     */
    public function destroy(User $user)
    {
        $user->followers()->detach(request()->user()->id);

        return response()->noContent();
    }
}
