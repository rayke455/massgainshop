<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CategoryStoreRequest;
use App\Http\Requests\Admin\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\SocialMedia;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    use Upload;

    public function index()
    {
        $data['categoryData'] = collect(Category::selectRaw('COUNT(id) AS totalCategory')
            ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS inactiveCategory')
            ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS activeCategory')
            ->get()
            ->toArray())->collapse();
        return view('admin.category.index', $data);
    }

    public function categoryShowingWithDatatable(Request $request)
    {
        $search = $request->search['value'];
        $filterStatus = $request->filterStatus;
        $filterName = $request->filterName;

        $category = Category::query()->orderBy('sort_by', 'ASC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('category_title', 'LIKE', "%{$search}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->when(!empty($filterName), function ($query) use ($filterName) {
                return $query->where('category_title', 'LIKE', "%{$filterName}%");
            });

        return DataTables::of($category)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                           class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                           data-id="' . $item->id . '">';
            })
            ->addColumn('name', function ($item) {
                return '<a class="d-flex align-items-center tr-href" href="javascript:void(0)" data-code="' . $item->id . '">
                                <div class="list-group-item">
                                                    <i class="sortablejs-custom-handle bi-grip-horizontal list-group-icon"></i>
                                                </div>
                                <div class="flex-shrink-0">
                                    <img class="avatar avatar-md" src="' . getFile($item->image_driver, $item->image) . '"
                                         alt="Image Description">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="text-inherit mb-0">' . $item->category_title . '</h5>
                                </div>
                            </a>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Inactive') . '
                  </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.category.edit', $item->id);
                $deleteRoute = route('admin.category.destroy', $item->id);
                $singleActDeActRoute = route('admin.category.change.status', $item->id);
                $statusText = $item->status == 0 ? 'activate' : 'deactivate';
                $statusIcon = $item->status == 0 ? 'fa-check' : 'fa-ban';
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="categoryEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="categoryEditDropdown">

                        <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                            data-route="' . $deleteRoute . '"
                            data-bs-toggle="modal" data-bs-target="#singleDeleteModal">
                            <i class="bi-trash dropdown-item-icon"></i> ' . trans("Delete") . '
                        </a>

                        <a class="dropdown-item status-change" href="javascript:void(0)"
                            data-text="' . $statusText . '"
                            data-route="' . $singleActDeActRoute . '"
                            data-status="' . $item->status . '"
                            data-bs-toggle="modal" data-bs-target="#singleActivateDeactivateModal">
                            <i class="fa-light ' . $statusIcon . ' dropdown-item-icon"></i> ' . ucfirst($statusText) . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'name', 'status'])
            ->make(true);
    }

    public function create()
    {
        $data['socialMedia'] = SocialMedia::orderBy('id', 'desc')->get();
        return view('admin.category.create', $data);
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, config('filelocation.category.path'), null, null, 'webp', 80);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
            }

            $categoryRes = Category::create([
                'social_media_id' => $request->social_media,
                'category_title' => $request->name,
                'status' => $request->status,
                'image' => $image['path'] ?? null,
                'image_driver' => $image['driver'] ?? null
            ]);

            throw_if(!$categoryRes, 'Something went wrong, while storing the category data.');

            return back()->with('success', 'Category created successfully.');

        } catch (\Exception $exception) {
            if (isset($image))
                $this->fileDelete($image['driver'], $image['path']);
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $data['socialMedia'] = SocialMedia::orderBy('id', 'desc')->get();
            $data['category'] = Category::where('id', $id)->firstOr(function () {
                throw new \Exception('Category is not available.');
            });
            return view('admin.category.edit', $data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function update(CategoryUpdateRequest $request, string $id)
    {
        try {

            $category = Category::where('id', $id)->firstOr(function () {
                throw new \Exception('Category is not available.');
            });

            if ($request->hasFile('image')) {
                $image = $this->fileUpload($request->image, config('filelocation.category.path'), null, null, 'webp', 80, $category->image, $category->image_driver);
                throw_if(empty($image['path']), 'Image could not be uploaded.');
            }

            $categoryRes = $category->update([
                'social_media_id' => $request->social_media,
                'category_title' => $request->name,
                'status' => $request->status,
                'image' => $image['path'] ?? $category->image,
                'image_driver' => $image['driver'] ?? $category->image_driver
            ]);

            throw_if(!$categoryRes, 'Something went wrong, while updating the category data.');

            return back()->with('success', 'Category updated successfully.');

        } catch (\Exception $exception) {
            if (isset($image))
                $this->fileDelete($image['driver'], $image['path']);
            return back()->with('error', $exception->getMessage());
        }
    }


    public function destroy(string $id)
    {
        try {
            $categoryData = Category::with('service')->where('id', $id)->firstOr(function () {
                throw new \Exception('category is not available.');
            });

            if ($categoryData->has('service'))
                return back()->with('error', 'You can not delete this category.Category has one or more services.');

            $categoryData->delete();
            $this->fileDelete($categoryData->image_driver, $categoryData->driver);

            return back()->with('success', 'Category deleted successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $categoryStatus = Category::where('id', $id)->firstOr(function () {
                throw new \Exception('Category is not available.');
            });

            $categoryStatus->update([
                'status' => $request->status == 0 ? 1 : 0
            ]);

            throw_if(!$categoryStatus, 'Something went wrong, Please try again.');

            return back()->with('success', 'Category status changed successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function activeInactive(Request $request)
    {
        if ($request->strIds === null) {
            session()->flash('error', 'You did not select any category.');
            return response()->json(['error' => 1]);
        } else {
            Category::whereIn('id', $request->strIds)->each(function ($category) use ($request) {
                $category->update([
                    'status' => $request->status
                ]);
            });
            session()->flash('success', 'Categories have been updated successfully.');
            return response()->json(['success' => 1]);
        }
    }

    public function deleteMultiple(Request $request)
    {
        if (empty($request->strIds)) {
            session()->flash('error', 'You did not select any category.');
            return response()->json(['error' => 1]);
        }

        $categoryIds = $request->strIds;

        $deletableCategories = Category::whereIn('id', $categoryIds)
            ->doesntHave('service')
            ->pluck('id');

        Category::whereIn('id', $deletableCategories)->delete();

        $undeleted = array_diff($categoryIds, $deletableCategories->toArray());

        if (count($undeleted) > 0) {
            session()->flash('error', 'Some categories were not deleted because they have services.');
            return response()->json(['error' => 1]);
        }

        session()->flash('success', 'Categories have been deleted successfully.');
        return response()->json(['success' => 1]);
    }

    public function sorting(Request $request)
    {
        $sortItems = $request->sort;
        foreach ($sortItems as $key => $value) {
            Category::where('id', $value)->update(['sort_by' => $key + 1]);
        }
    }
}
