<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogDetails;
use App\Models\ContentDetails;
use App\Models\Page;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blogDetails(Request $request, $slug)
    {
        try {
            $data['footer'] = footerData();
            $data['blogCategory'] = BlogCategory::with('blog')->where('status', 1)->orderBy('id', 'desc')->get();
            $blogDetails = BlogDetails::with([
                'blog' => function ($query) {
                    $query->where('status', 1);
                },
                'blog.author'
            ])->where('slug', $slug)->firstOrFail();

            $currentViews = optional($blogDetails->blog)->views;
            $newViews = $currentViews + 1;

            $blogDetails->blog->update([
                'views' => $newViews,
            ]);

            $blogPage = Page::with('details:id,page_id,language_id,name')
                ->where('template_name', basicControl()->theme)
                ->where('slug', 'blogs')
                ->first();

            $data['pageSeo'] = [
                'page_title' => optional($blogDetails->blog)->page_title,
                'meta_title' => optional($blogDetails->blog)->meta_title,
                'meta_keywords' => implode(',', optional($blogDetails->blog)->meta_keywords ?? []),
                'meta_description' => optional($blogDetails->blog)->meta_description,
                'og_description' => optional($blogDetails->blog)->og_description,
                'meta_robots' => optional($blogDetails->blog)->meta_robots,
                'meta_image' => getFile(optional($blogDetails->blog)->meta_image_driver, optional($blogDetails->blog)->meta_image),
                'breadcrumb_image' => $blogPage->breadcrumb_status ?
                    getFile($blogPage->breadcrumb_image_driver, $blogPage->breadcrumb_image) : null,
            ];
            $data['recentPosts'] = Blog::with('details')->latest()->take(3)->get();
            return view(template() . 'blog_details', $data, compact('blogDetails'));
        } catch (\Exception $exception) {
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function categoryWiseBlog(Request $request)
    {
        try {
            $search = $request->all();
            $data['blogs'] = Blog::with('category', 'details', 'author')
                ->when(isset($search['search']), function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->whereHas('details', function ($query) use ($search) {
                            $query->where('title', 'like', "%{$search['search']}%");
                        })
                            ->orWhereHas('category.details', function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search['search']}%");
                            });
                    });
                })
                ->when(!empty($search['category']), function ($query) use ($search) {
                    $query->whereHas('category.details', function ($qry) use ($search) {
                        $qry->where('slug', $search['category']);
                    });
                })
                ->orderBy('id', 'desc')
                ->paginate(basicControl()->paginate);

            $page = Page::with('details:id,page_id,language_id,name')->where('template_name', basicControl()->theme)
                ->where('name', 'blogs')
                ->first();

            $data['blogContent'] = ContentDetails::with('content')
                ->whereHas('content', function ($query) {
                    $query->where('theme', basicControl()->theme)
                        ->where('name', 'blog')
                        ->where('type', 'single');
                })
                ->first();

            $data['pageSeo'] = [
                'page_title' => $page->page_title,
                'meta_title' => $page->meta_title,
                'meta_keywords' => implode(',', $page->meta_keywords ?? []),
                'meta_description' => $page->meta_description,
                'og_description' => $page->og_description,
                'meta_robots' => $page->meta_robots,
                'meta_image' => getFile($page->meta_image_driver, $page->meta_image),
                'breadcrumb_image' => $page->breadcrumb_status ?
                    getFile($page->breadcrumb_image_driver, $page->breadcrumb_image) : null,
            ];
            return view(template() . 'category_wise_blog', $data);
        } catch (\Exception $exception) {
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function author($slug)
    {
        try {
            $data['footer'] = footerData();

            $page = Page::with('details:id,page_id,language_id,name')
                ->where('template_name', basicControl()->theme)
                ->where('name', 'blogs')
                ->first();

            $data['pageSeo'] = [
                'page_title' => 'Author',
                'breadcrumb_image' => $page->breadcrumb_status ?
                    getFile($page->breadcrumb_image_driver, $page->breadcrumb_image) : null,
            ];

            $author = Author::whereHas('details', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->with('details')->firstOr(function () {
                throw new \Exception('Author is not available.');
            });

            $data['blogs'] = Blog::with(['details' => function ($query) {
                $query->select('id', 'blog_id', 'title', 'slug', 'description');
            }])
                ->where('author_id', $author->id)
                ->orderBy('id', 'desc')
                ->take(3)
                ->get();

            $data['blogContent'] = ContentDetails::with('content')
                ->whereHas('content', function ($query) {
                    $query->where('theme', basicControl()->theme)
                        ->where('name', 'blog')->where('type', 'single');
                })
                ->first();

            return view(template() . 'author', $data, compact('author'));
        } catch (\Exception $exception) {
            return back()->with('error', 'Something went wrong; keep patience.');
        }
    }
}
