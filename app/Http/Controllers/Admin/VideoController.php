<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Tag;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\VideoComment;
use App\Models\VideoTag;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Auth;

class VideoController extends Controller
{
    use General, ImageSaveTrait;

    protected $model;
    public function __construct(Video $video)
    {
        $this->model = new Crud($video);
    }


    public function index()
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $data['title'] = 'Manage Video';
        $data['videos'] = $this->model->getOrderById('DESC', 25);
        return view('admin.video.index', $data);
    }


    public function create()
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $data['title'] = 'Create Video';
        $data['videoCategories'] = VideoCategory::all();
        $data['tags'] = Tag::all();
        return view('admin.video.create', $data);
    }


    public function store(VideoRequest $request)
    {

        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        if (Video::query()->where('slug', $request->slug)->count() > 0)
        {
            $slug = getSlug($request->slug) . '-'. rand(100000, 999999);
        } else {
            $slug = getSlug($request->slug);
        }

        $data = [
            'title' => $request->title,
            'slug' => $slug,
            'details' => $request->details,
            'description' => $request->description,
            'keywords' => $request->keywords,
            'video_category_id' => $request->video_category_id,
            'image' => $request->image ? $this->saveImage('video', $request->image, null, null) :   null,
            'video' => $request->video,
        ];

        $video = $this->model->create($data); // create new Video

        if ($request->tag_ids){
            foreach ($request->tag_ids as $tag_id){
                $videoTag = new VideoTag();
                $videoTag->video_id = $video->id;
                $videoTag->tag_id = $tag_id;
                $videoTag->save();
            }
        }


        return $this->controlRedirection($request, 'video', 'Video');
    }


    public function edit($uuid)
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $data['title'] = 'Edit Video';
        $data['video'] = $this->model->getRecordByUuid($uuid);
        $data['videoTags'] = $data['video']->tags->pluck('tag_id')->toArray();
        $data['videoCategories'] = VideoCategory::all();
        $data['tags'] = Tag::all();
        return view('admin.video.edit', $data);
    }


    public function update(VideoRequest $request, $uuid)
    {

        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $video = $this->model->getRecordByUuid($uuid);

        if ($request->image)
        {
            $this->deleteFile($video->image); // delete file from server

            $image = $this->saveImage('video', $request->image, null, null); // new file upload into server

        } else {
            $image = $video->image;
        }

        if (Video::query()->where('slug', $request->slug)->where('uuid', '!=', $uuid)->count() > 0)
        {
            $slug = getSlug($request->slug) . '-'. rand(100000, 999999);
        } else {
            $slug = getSlug($request->slug);
        }

        $data = [
            'title' => $request->title,
            'slug' => $slug,
            'details' => $request->details,
            'video_category_id' => $request->video_category_id,
            'image' => $image,
            'video' => $request->video,
            'description' => $request->description,
            'keywords' => $request->keywords,
            'status'=> $request->status
        ];

        $video = $this->model->updateByUuid($data, $uuid); // update category

        if ($request->tag_ids){
            VideoTag::where('video_id', $video->id)->delete();
            foreach ($request->tag_ids as $tag_id){
                $videoTag = new VideoTag();
                $videoTag->video_id = $video->id;
                $videoTag->tag_id = $tag_id;
                $videoTag->save();
            }
        }

        return $this->controlRedirection($request, 'video', 'Video');
    }


    public function delete($uuid)
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $video = $this->model->getRecordByUuid($uuid);
        VideoTag::where('video_id', $video->id)->delete();
        $this->deleteFile($video->image); // delete file from server
        $this->model->deleteByUuid($uuid); // delete record

        $this->showToastrMessage('error', __('Video has been deleted'));
        return redirect()->back();
    }





    public function videoCommentList()
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $data['title'] = ' Video Comments';
        $data['navVideoParentActiveClass'] = 'mm-active';
        $data['subNavVideoCommentListActiveClass'] = 'mm-active';

        $data['comments'] = VideoComment::paginate(25);
        return view('admin.video.comment-list', $data);

    }

    public function changeVideoCommentStatus(Request $request)
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }


        $comment = VideoComment::findOrFail($request->id);
        $comment->status = $request->status;
        $comment->save();

        return response()->json([
            'data' => 'success',
        ]);
    }

    public function videoCommentDelete($id)
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $comment = VideoComment::findOrFail($id);
        VideoComment::where('parent_id', $id)->delete();
        $comment->delete();

        $this->showToastrMessage('error', __('Video has been deleted'));
        return redirect()->back();
    }




}
