<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\VideoCategory;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Auth;

class VideoCategoryController extends Controller
{
    use General;

    protected $model;

    public function __construct(VideoCategory $category)
    {
        $this->model = new Crud($category);
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $data['title'] = 'Manage Video Category';
        $data['navVideoActiveClass'] = "mm-active";
        $data['subNavVideoCategoryIndexActiveClass'] = "mm-active";
        $data['videoCategories'] = $this->model->getOrderById('DESC', 25);
        return view('admin.video.category-index', $data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $slug = getSlug($request->name);

        if (VideoCategory::query()->where('slug', $slug)->count() > 0)
        {
            $slug = getSlug($request->name) . '-'. rand(100000, 999999);
        }
        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->status,
        ];

        $this->model->create($data);

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $uuid
     * @return RedirectResponse
     */
    public function update(Request $request, $uuid): RedirectResponse
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'status' => $request->status,
        ];

        $this->model->updateByUuid($data, $uuid);
        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    /**
     * @param $uuid
     * @return RedirectResponse
     */
    public function delete($uuid): RedirectResponse
    {
        if (!Auth::user()->can('manage_video')) {
            abort('403');
        } // end permission checking

        $this->model->deleteByUuid($uuid); // delete record
        $this->showToastrMessage('error', __('Video Category has been deleted'));
        return redirect()->back();
    }
}
