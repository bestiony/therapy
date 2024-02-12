<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\VideoComment;
use App\Models\VideoTag;
use App\Traits\General;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    use General;
    public function videoAll()
    {
        $data['pageTitle'] = "Video";
        $data['metaData'] = staticMeta(5);
        $data['videos'] = Video::whereHas('user')->latest()->active()->paginate(10);
        $data['recentVideos'] = Video::latest()->take(3)->active()->get();
        $data['videoCategories'] = VideoCategory::withCount('activeVideos')->active()->get();
        $data['tags'] = Tag::all();

        return view('frontend.video.videos', $data);
    }

    public function videoDetails($slug)
    {
        $data['pageTitle'] = "Video Details";
        $data['metaData'] = staticMeta(6);
        $data['video'] = Video::with('tags')->whereSlug($slug)->firstOrFail();


        $tag_ids = VideoTag::whereVideoId($data['video']->id)->pluck('tag_id')->toArray();
        $data['tags'] = Tag::whereIn('id', $tag_ids)->get();
        $data['recentVideos'] = Video::latest()->take(3)->active()->get();
        $data['videoCategories'] = VideoCategory::withCount('videos')->active()->get();
        $data['videoComments'] = VideoComment::active();
        $data['videoComments'] = $data['videoComments']->where('video_id', $data['video']->id)->whereNull('parent_id')->get();
        return view('frontend.video.video-details', $data);
    }

    public function categoryVideos($slug)
    {
        $data['videoCategory'] = VideoCategory::whereSlug($slug)->firstOrFail();
        $data['pageTitle'] = $data['videoCategory']->name;
        $video = Video::whereVideoCategoryId($data['videoCategory']->id);
        $data['videos'] = Video::active()->whereVideoCategoryId($data['videoCategory']->id)->paginate(10);
        $data['recentVideos'] = $video->latest()->active()->take(3)->get();
        $data['videoCategories'] = VideoCategory::withCount('activeVideos')->active()->get();
        $data['tags'] = Tag::all();

        return view('frontend.video.category-videos', $data);
    }


    public function videoCommentStore(Request $request)
    {

        $comment = new VideoComment();
        $comment->video_id = $request->video_id;
        $comment->user_id = $request->user_id;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->status = 1;
        $comment->save();

        $data['videoComments'] = VideoComment::active();
        $data['videoComments'] = $data['videoComments']->where('video_id', $request->video_id)->whereNull('parent_id')->get();
        return view('frontend.video.render-comment-list', $data);
    }

    public function videoCommentReplyStore(Request $request)
    {
        if ($request->user_id && $request->comment){
            $comment = new VideoComment();
            $comment->video_id = $request->video_id;
            $comment->user_id = $request->user_id;
            $comment->name = $request->name;
            $comment->email = $request->email;
            $comment->comment = $request->comment;
            $comment->status = 1;
            $comment->parent_id = $request->parent_id;
            $comment->save();

            $this->showToastrMessage('success', __('Reply successfully.')) ;
            return redirect()->back();
        } else {
            $this->showToastrMessage('warning', __('You need to login first!')) ;
            return redirect()->back();
        }
    }

    public function searchVideoList(Request $request)
    {
        $data['videos'] = Video::active()->where('title', 'like', "%{$request->title}%")->get();
        return view('frontend.video.render-search-video-list', $data);
    }


}
