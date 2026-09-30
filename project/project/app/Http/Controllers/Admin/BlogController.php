<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogUpdateRequest;
use App\Http\Requests\Admin\SeoUpdateRequest;
use App\Http\Requests\Admin\BlogStoreRequest;
use App\Models\Author;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogDetails;
use App\Models\Language;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    use Upload;

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        $data['allLanguage'] = Language::select('id', 'name', 'short_name', 'flag', 'flag_driver')->where('status', 1)->get();
        $data['blogs'] = Blog::with('category', 'details')
            ->orderBy('id', 'desc')->paginate(basicControl()->paginate);
        return view('admin.blog.list', $data);
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data['authors'] = Author::orderBy('id', 'desc')->get();
        $data['blogCategory'] = BlogCategory::where('status', 1)->orderBy('id', 'desc')->get();
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        return view('admin.blog.create', $data);
    }

    public function store(BlogStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            if ($request->hasFile('thumbnail_image')) {
                $thumbnailImage = $this->fileUpload($request->thumbnail_image, config('filelocation.blog.path'), null, null, 'webp', 70, null, null);
                throw_if(empty($thumbnailImage['path']), 'Thumbnail image could not be uploaded.');
            }

            if ($request->hasFile('thumbnail_image_two')) {
                $thumbnailImageTwo = $this->fileUpload($request->thumbnail_image_two, config('filelocation.blog.path'), null, null, 'webp', 70, null, null);
                throw_if(empty($thumbnailImageTwo['path']), 'Thumbnail image Two could not be uploaded.');
            }

            if ($request->hasFile('description_image')) {
                $descriptionImage = $this->fileUpload($request->description_image, config('filelocation.blog.path'), null, null, 'webp', 70, null, null);
                throw_if(empty($descriptionImage['path']), 'Description image could not be uploaded.');
            }

            if ($request->hasFile('breadcrumb_image')) {
                $breadcrumbImage = $this->fileUpload($request->breadcrumb_image, config('filelocation.blog.path'), null, null, 'webp', 70, null, null);
                throw_if(empty($breadcrumbImage['path']), 'Breadcrumb image could not be uploaded.');
            }

            $response = Blog::create([
                'category_id' => $request->category_id,
                'author_id' => $request->author_id,
                'thumbnail_image' => $thumbnailImage['path'] ?? null,
                'thumbnail_image_driver' => $thumbnailImage['driver'] ?? null,
                'thumbnail_image_two' => $thumbnailImageTwo['path'] ?? null,
                'thumbnail_image_two_driver' => $thumbnailImageTwo['driver'] ?? null,
                'description_image' => $descriptionImage['path'] ?? null,
                'description_image_driver' => $descriptionImage['driver'] ?? null,
                'breadcrumb_image' => $breadcrumbImage['path'] ?? null,
                'breadcrumb_image_driver' => $breadcrumbImage['driver'] ?? null,
                'breadcrumb_status' => $request->breadcrumb_status,
                'status' => $request->status,
            ]);
            throw_if(!$response, 'Something went wrong, while storing blog. Please try again later.');

            $response->details()->create([
                'language_id' => $request->language_id,
                "title" => $request->title,
                "slug" => $request->slug,
                'tags' => $request->tags,
                'description' => $request->description,
                'quote' => $request->quote,
                'quote_author' => $request->quote_author,
            ]);
            return back()->with('success', 'Blog created successfully.');
        } catch (\Exception $e) {
            if (isset($thumbnailImage))
                $this->fileDelete($thumbnailImage['driver'], $thumbnailImage['path']);
            if (isset($descriptionImage))
                $this->fileDelete($descriptionImage['driver'], $descriptionImage['path']);
            if (isset($breadcrumbImage))
                $this->fileDelete($breadcrumbImage['driver'], $breadcrumbImage['path']);
            return back()->with('error', $e->getMessage());
        }
    }


    public function edit($id, $language = null): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $blog = Blog::with(['details' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }])->where('id', $id)->firstOr(function () {
                throw new \Exception('The blog was not found.');
            });
            $data['authors'] = Author::orderBy('id', 'desc')->get();
            $data['pageEditableLanguage'] = Language::where('id', $language)->select('id', 'name', 'short_name')->first();
            $data['blogCategory'] = BlogCategory::where('status', 1)->orderBy('id', 'desc')->get();
            $data['allLanguage'] = Language::select('id', 'name', 'short_name', 'flag', 'flag_driver')->where('status', 1)->get();
            return view('admin.blog.edit', $data, compact('blog', 'language'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(BlogUpdateRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $blog = Blog::where('id', $id)->firstOr(function () {
                throw new \Exception('Blog is not available.');
            });

            if ($request->hasFile('thumbnail_image')) {
                $thumbnailImage = $this->fileUpload($request->thumbnail_image, config('filelocation.blog.path'), null, config('filelocation.blog.size.thumb_image'), 'webp', 70, $blog->thumbnail_image, $blog->thumbnail_image_driver);
                throw_if(empty($thumbnailImage['path']), 'Thumbnail image could not be uploaded.');
            }

            if ($request->hasFile('thumbnail_image_two')) {
                $thumbnailImageTwo = $this->fileUpload($request->thumbnail_image_two, config('filelocation.blog.path'), null, config('filelocation.blog.size.thumb_image_two'), 'webp', 70, $blog->thumbnail_image_two, $blog->thumbnail_image_two_driver);
                throw_if(empty($thumbnailImageTwo['path']), 'Thumbnail image Two could not be uploaded.');
            }

            if ($request->hasFile('description_image')) {
                $descriptionImage = $this->fileUpload($request->description_image, config('filelocation.blog.path'), null, config('filelocation.blog.size.description_image'), 'webp', 70, $blog->description_image, $blog->description_image_driver);
                throw_if(empty($descriptionImage['path']), 'Description image could not be uploaded.');
            }

            if ($request->hasFile('breadcrumb_image')) {
                $breadcrumbImage = $this->fileUpload($request->breadcrumb_image, config('filelocation.blog.path'), null, config('filelocation.blog.size.breadcrumb_image'), 'webp', 70, $blog->breadcrumb_image, $blog->breadcrumb_image_driver);
                throw_if(empty($breadcrumbImage['path']), 'Breadcrumb image could not be uploaded.');
            }

            $blog->update([
                'category_id' => $request->category_id,
                "author_id" => $request->author_id,
                'thumbnail_image' => $thumbnailImage['path'] ?? $blog->thumbnail_image,
                'thumbnail_image_driver' => $thumbnailImage['driver'] ?? $blog->thumbnail_image_driver,
                'thumbnail_image_two' => $thumbnailImageTwo['path'] ?? $blog->thumbnail_image_two,
                'thumbnail_image_two_driver' => $thumbnailImageTwo['driver'] ?? $blog->thumbnail_image_two_driver,
                'description_image' => $descriptionImage['path'] ?? $blog->description_image,
                'description_image_driver' => $descriptionImage['driver'] ?? $blog->description_image_driver,
                'breadcrumb_image' => $breadcrumbImage['path'] ?? $blog->breadcrumb_image,
                'breadcrumb_image_driver' => $breadcrumbImage['driver'] ?? $blog->breadcrumb_image_driver,
                'breadcrumb_status' => $request->breadcrumb_status,
                'status' => $request->status,
            ]);
            throw_if(!$blog, 'Something went wrong, while storing blog. Please try again later.');

            $blog->details()->updateOrCreate(
                [
                    'language_id' => $request->language_id
                ],
                [
                    "title" => $request->title,
                    "slug" => $request->slug,
                    'tags' => $request->tags,
                    'description' => $request->description,
                    'quote' => $request->quote,
                    'quote_author' => $request->quote_author,
                ]);
            return back()->with('success', 'Blog updated successfully.');
        } catch (\Exception $e) {
            if (isset($thumbnailImage))
                $this->fileDelete($thumbnailImage['driver'], $thumbnailImage['path']);
            if (isset($descriptionImage))
                $this->fileDelete($descriptionImage['driver'], $descriptionImage['path']);
            if (isset($breadcrumbImage))
                $this->fileDelete($breadcrumbImage['driver'], $breadcrumbImage['path']);
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $blog = Blog::where('id', $id)->firstOr(function () {
                throw new \Exception('Blog was not found.');
            });
            $blogDetailsData = BlogDetails::where('blog_id', $id)->get();
            foreach ($blogDetailsData as $item) {
                $item->delete();
            }
            $this->fileDelete($blog->thumbnail_image_driver, $blog->thumbnail_image);
            $this->fileDelete($blog->description_image_driver, $blog->description_image);
            $this->fileDelete($blog->breadcrumb_image_driver, $blog->breadcrumb_image);
            $blog->delete();
            return back()->with('success', 'Blog deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function slugUpdate(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $rules = [
            "blogId" => "required|exists:blogs,id",
            'newSlug' => 'required|string|alpha_dash||min:1|max:100|unique:blog_details,slug,' . $request->blogId,
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $blog = Blog::find($request->blogId);
        if (!$blog) {
            return back()->with("error", "Blog is not available");
        }

        $blog->details()->update([
            'slug' => $request->newSlug
        ]);

        return response([
            'success' => true,
            'slug' => $blog->slug
        ]);
    }

    public function seo(Request $request, $id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $blog = Blog::where('id', $id)->firstOr(function () {
                throw new \Exception('Blog not found');
            });
            return view('admin.blog.seo', compact('blog'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function seoUpdate(SeoUpdateRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $blog = Blog::where('id', $id)->firstOr(function () {
                throw new \Exception('Blog not found.');
            });

            if ($request->hasFile('meta_image')) {
                $metaImage = $this->fileUpload($request->meta_image, config('filelocation.blog.path'), null, null, 'webp', 60, $blog->meta_image, $blog->meta_image_driver);
                throw_if(empty($metaImage['path']), 'Meta image could not be uploaded.');
            }

            if ($request->meta_robots) {
                $meta_robots = implode(",", $request->meta_robots);
            }
            $response = $blog->update([
                'page_title' => $request->page_title,
                'meta_title' => $request->meta_title,
                'meta_keywords' => $request->meta_keywords,
                'meta_description' => $request->meta_description,
                'og_description' => $request->og_description,
                'meta_robots' => $meta_robots ?? null,
                'meta_image' => $metaImage['path'] ?? $blog->meta_image,
                'meta_image_driver' => $metaImage['driver'] ?? $blog->meta_image_driver,
                'is_static_footer' => $request->is_static_footer
            ]);

            throw_if(!$response, 'Something went wrong while updating blog seo data. Please try again later.');
            return back()->with('success', 'Blog seo updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
