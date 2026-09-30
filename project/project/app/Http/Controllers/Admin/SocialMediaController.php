<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialMedia\SocialMediaStoreRequest;
use App\Models\SocialMedia;
use App\Traits\Upload;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    use Upload;

    public function index(Request $request)
    {
        $data['socialMediaData'] = collect(SocialMedia::selectRaw('COUNT(id) AS totalSocialMedia')
            ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS inactiveSocialMedia')
            ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS activeSocialMedia')
            ->get()
            ->toArray())->collapse();
        $search = $request->all();
        $data['socialMedia'] = SocialMedia::when(!empty($search['name']), function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search['name'] . '%');
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->orderBy('sort_by', 'asc')
            ->paginate(basicControl()->paginate);
        return view('admin.social_media.index', $data);
    }

    public function create()
    {
        return view('admin.social_media.create');
    }

    public function store(SocialMediaStoreRequest $request)
    {
        try {
            if ($request->hasFile('icon')) {
                $icon = $this->fileUpload($request->icon, config('filelocation.social_media.path'), null, null, 'webp', 80);
                throw_if(empty($icon['path']), 'Icon could not be uploaded.');
            }

            $socialMediaRes = SocialMedia::create([
                'name' => $request->name,
                'status' => $request->status,
                'icon' => $icon['path'] ?? null,
                'icon_driver' => $icon['driver'] ?? null
            ]);

            throw_if(!$socialMediaRes, 'Something went wrong, while storing the social media data.');

            return back()->with('success', 'Social Media added successfully.');
        } catch (\Exception $exception) {
            if (isset($icon))
                $this->fileDelete($icon['driver'], $icon['path']);
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $data['social_media'] = SocialMedia::where('id', $id)->firstOr(function () {
                throw new \Exception('Social Media is not available.');
            });
            return view('admin.social_media.edit', $data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function update(SocialMediaStoreRequest $request, string $id)
    {
        try {
            $socialMedia = SocialMedia::where('id', $id)->firstOr(function () {
                throw new \Exception('Social media is not available.');
            });

            if ($request->hasFile('icon')) {
                $icon = $this->fileUpload($request->icon, config('filelocation.social_media.path'), null, null, 'webp', 80, $socialMedia->icon, $socialMedia->icon_driver);
                throw_if(empty($icon['path']), 'Icon could not be uploaded.');
            }

            $socialMediaRes = $socialMedia->update([
                'name' => $request->name,
                'status' => $request->status,
                'icon' => $icon['path'] ?? $socialMedia->icon,
                'icon_driver' => $icon['driver'] ?? $socialMedia->icon_driver
            ]);

            throw_if(!$socialMediaRes, 'Something went wrong, while updating the social media data.');

            return back()->with('success', 'Social media updated successfully.');

        } catch (\Exception $exception) {
            if (isset($icon))
                $this->fileDelete($icon['driver'], $icon['path']);
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {

            $socialMediaData = SocialMedia::where('id', $id)->firstOr(function () {
                throw new \Exception('Social media is not available.');
            });

            $socialMediaData->delete();

            $this->fileDelete($socialMediaData->icon_driver, $socialMediaData->driver);

            return back()->with('success', 'Social media deleted successfully.');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function statusChange(Request $request, $id)
    {
        try {
            $socialMedia = SocialMedia::where('id', $id)->firstOr(function () {
                throw new \Exception('Social media is not available.');
            });

            $socialMedia->update([
                'status' => $request->status == 0 ? 1 : 0
            ]);

            throw_if(!$socialMedia, 'Something went wrong, Please try again.');

            return back()->with('success', 'Social media status changed successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function sortBy(Request $request)
    {
        $sortItems = $request->sort;
        foreach ($sortItems as $key => $value) {
            SocialMedia::where('id', $value)->update(['sort_by' => $key + 1]);
        }
    }
}
