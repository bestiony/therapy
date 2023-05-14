<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    public function followers()
    {
        $data['title'] = 'Followers';
        $data['navFollowersActiveClass'] = 'active';
        $data['followers'] = auth()->user()->followers;
        return view('certified_parent.follow.followers', $data);
    }

    public function followings()
    {
        $data['title'] = 'Followings';
        $data['navFollowingsActiveClass'] = 'active';
        $data['followings'] = auth()->user()->followings;
        return view('certified_parent.follow.followings', $data);
    }
}
