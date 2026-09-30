<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthorStoreRequest;
use App\Http\Requests\Admin\AuthorUpdateRequest;
use App\Models\Author;
use App\Models\Language;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{

    use Upload;

    public function index()
    {
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        $data['allLanguage'] = Language::select('id', 'name', 'short_name', 'flag', 'flag_driver')->where('status', 1)->get();
        $data['authors'] = Author::orderBy('id', 'desc')->get();
        return view('admin.author.index', $data);
    }

    public function create()
    {
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        return view('admin.author.create', $data);
    }

    public function store(AuthorStoreRequest $request)
    {
        try {
            if ($request->hasFile('image')) {
                $size = config('filelocation.author.size.image');
                $image = $this->fileUpload($request->image, config('filelocation.blog.path'), null, null, 'webp', 80);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
            }

            $socialMediaArray = [];
            if ($request->has('social_media_name')) {
                for ($i = 0; $i < count($request->social_media_name); $i++) {
                    $socialMedia = [];
                    $socialMedia['social_media_name'] = $request->social_media_name[$i];
                    $socialMedia['icon'] = $request->icon[$i];
                    $socialMedia['link'] = $request->link[$i];
                    $socialMediaArray[] = $socialMedia;
                }
            }

            $author = Author::create([
                'image' => $image['path'],
                'image_driver' => $image['driver'],
                'status' => $request->status
            ]);

            $authorResponse = $author->details()->create([
                'language_id' => $request->language_id,
                'name' => $request->name,
                'slug' => $request->slug,
                'address' => $request->address,
                'description' => $request->description,
                'social_media' => $socialMediaArray,
            ]);

            throw_if(!$authorResponse, 'Something went wrong, while storing the author data.');

            return back()->with('success', 'Author created successfully.');
        } catch (\Exception $exception) {
            if (isset($image))
                $this->fileDelete($image['driver'], $image['path']);
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit(string $id, $language = null)
    {
        try {
            $data['pageEditableLanguage'] = Language::where('id', $language)
                ->select('id', 'name', 'short_name')
                ->first();

            $data['author'] = Author::with(['details' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }])->where('id', $id)->firstOr(function () {
                throw new \Exception('Author is not available.');
            });
            return view('admin.author.edit', $data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(AuthorUpdateRequest $request, string $id, $language = null): \Illuminate\Http\RedirectResponse
    {
        try {

            $author = Author::where('id', $id)->firstOr(function () {
                throw new \Exception('Author is not available.');
            });


            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, config('filelocation.blog.path'), null, null, 'webp', 70, $author->image, $author->image_driver);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
            }

            $socialMediaArray = [];
            if ($request->has('social_media_name')) {
                for ($i = 0; $i < count($request->social_media_name); $i++) {
                    $socialMedia = [];
                    $socialMedia['social_media_name'] = $request->social_media_name[$i];
                    $socialMedia['icon'] = $request->icon[$i];
                    $socialMedia['link'] = $request->link[$i];
                    $socialMediaArray[] = $socialMedia;
                }
            }

            $author->update([
                'image' => $image['path'] ?? $author->image,
                'image_driver' => $image['driver'] ?? $author->image_driver,
                'status' => $request->status
            ]);

            $author->details()->updateOrCreate(
                [
                    'language_id' => $language
                ],
                [
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'address' => $request->address,
                    'description' => $request->description,
                    'social_media' => $socialMediaArray,
                ]);
            throw_if(!$author, 'Something went wrong, while storing the author data.');
            return back()->with('success', 'Author updated successfully.');
        } catch (\Exception $exception) {
            if (isset($image))
                $this->fileDelete($image['driver'], $image['path']);
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $author = Author::where('id', $id)->firstOr(function () {
                throw new \Exception('Author is not available.');
            });
            $author->delete();
            $this->fileDelete($author->image_driver, $author->image);
            return back()->with('success', 'Author deleted successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function slugUpdate(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $rules = [
            "author_id" => "required|exists:authors,id",
            'newSlug' => 'required|string|alpha_dash||min:1|max:100|unique:authors,slug,' . $request->author_id,
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $author = Author::find($request->author_id);
        if (!$author) {
            return back()->with("error", "Author is not available");
        }

        $author->update([
            'slug' => $request->newSlug
        ]);

        return response([
            'success' => true,
            'slug' => $author->slug
        ]);
    }
}
