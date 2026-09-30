<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogCategoryController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        $data['allLanguage'] = Language::select('id', 'name', 'short_name', 'flag', 'flag_driver')->where('status', 1)->get();
        $data['blogCategory'] = BlogCategory::orderBy('id', 'desc')->paginate(basicControl()->paginate);
        return view('admin.blog_category.list', $data);
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data['defaultLanguage'] = Language::where('default_status', true)->first();
        return view('admin.blog_category.create', $data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:blog_category_details,name',
            'status' => 'nullable|numeric|in:0,1'
        ]);
        try {
            $category = BlogCategory::create([
                'status' => $request->status
            ]);
            $response = $category->details()->create([
                'language_id' => $request->language_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            throw_if(!$response, 'Something went wrong while storing blog category data. Please try again later.');
            return back()->with('success', 'Blog category save successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id, $language = null): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $data['pageEditableLanguage'] = Language::where('id', $language)
                ->select('id', 'name', 'short_name')
                ->first();
            $data['blogCategory'] = BlogCategory::with(['details' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }])->where('id', $id)->firstOr(function () {
                throw new \Exception('No blog category data found.');
            });
            return view('admin.blog_category.edit', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:150',
                Rule::unique('blog_category_details', 'category_id')->ignore($id),
            ],
            'status' => 'nullable|numeric|in:0,1'
        ]);

        try {
            $blogCategory = BlogCategory::where('id', $id)->firstOr(function () {
                throw new \Exception('No blog category found.');
            });
            $blogCategory->update([
                'status' => $request->status
            ]);
            $response = $blogCategory->details()->updateOrCreate(
                [
                    'category_id' => $id,
                    'language_id' => $request->language_id,
                ],
                [
                    'name' => $request->name,
                    'slug' => Str::slug($request->name)
                ]);
            throw_if(!$response, 'Something went wrong while updating blog category data. Please try again later.');
            return back()->with('success', 'Blog category save successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $blogCategory = BlogCategory::where('id', $id)->firstOr(function () {
                throw new \Exception('No blog category data found.');
            });
            $blogCategory->delete();
            return redirect()->back()->with('success', 'Blog category deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
