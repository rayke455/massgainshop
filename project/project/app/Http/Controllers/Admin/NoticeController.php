<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notice\NoticeStoreRequest;
use App\Models\Language;
use App\Models\Notice;
use App\Traits\Upload;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    use Upload;

    public function index()
    {
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        $data['notices'] = Notice::with('details')
            ->orderBy('id', 'desc')->paginate(basicControl()->paginate);
        $data['allLanguage'] = Language::select('id', 'name', 'short_name', 'flag', 'flag_driver')->where('status', 1)->get();
        return view('admin.notice.index', $data);
    }

    public function create()
    {
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        return view('admin.notice.create', $data);
    }

    public function store(NoticeStoreRequest $request)
    {
        $data = $request->validated();
        try {
            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, config('filelocation.notice.path'), null, null, 'webp', 70);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
            }

            $response = Notice::create([
                'image' => $image['path'] ?? null,
                'image_driver' => $image['driver'] ?? null,
                'status' => $data['status'],
            ]);
            throw_if(!$response, 'Something went wrong, while storing notice. Please try again later.');

            $response->details()->create([
                'language_id' => $request->language_id,
                "title" => $data['title'],
                'description' => $data['description'],
            ]);
            return back()->with('success', 'Notice created successfully.');
        } catch (\Exception $e) {
            if (isset($image))
                $this->fileDelete($image['driver'], $image['path']);
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id, $language = null)
    {
        try {
            $notice = Notice::with(['details' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }])->where('id', $id)->firstOr(function () {
                throw new \Exception('The notice was not found.');
            });
            $data['pageEditableLanguage'] = Language::where('id', $language)->select('id', 'name', 'short_name')->first();
            return view('admin.notice.edit', $data, compact('notice', 'language'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(NoticeStoreRequest $request, string $id)
    {

        $data = $request->validated();
        try {
            $notice = Notice::where('id', $id)->firstOr(function () {
                throw new \Exception('notice is not available.');
            });

            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, config('filelocation.notice.path'), null, null, 'webp', 70, $notice->image, $notice->image_driver);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
            }

            $response = $notice->update([
                'image' => $image['path'] ?? $notice->image,
                'image_driver' => $image['driver'] ?? $notice->image_driver,
                'status' => $data['status'],
            ]);
            throw_if(!$response, 'Something went wrong, while updating notice. Please try again later.');

            $notice->details()->updateOrCreate([
                'language_id' => $request->language_id
            ],
                [
                    "title" => $data['title'],
                    'description' => $data['description'],
                ]);
            return back()->with('success', 'Notice updated successfully.');
        } catch (\Exception $e) {
            if (isset($image))
                $this->fileDelete($image['driver'], $image['path']);
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $notice = Notice::where('id', $id)->firstOr(function () {
                throw new \Exception('Notice was not found.');
            });
            $this->fileDelete($notice->image_driver, $notice->image);
            $notice->delete();
            return back()->with('success', 'Notice deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
