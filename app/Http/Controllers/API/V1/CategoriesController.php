<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\contracts\ApiController;
use App\repositories\Contracts\CategoryRepositoryInterface;

class CategoriesController extends ApiController
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {

    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'pagesize' => 'nullable|numeric'
        ]);

        $categories = $this->categoryRepository->paginate($request->search, $request->page, $request->pagesize??20, ['name','slug']);

        return $this->respondSuccess('دسته بندی ها', $categories);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'slug' => 'required|string|min:2|max:255'
        ]);

        $createdCategory = $this->categoryRepository->create([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return $this->respondCreated('دسته بندی ایجاد شد', [
            'name' => $createdCategory->getName(),
            'slug' => $createdCategory->getSlug()
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'name' => 'required|string|min:2|max:255',
            'slug' => 'required|string|min:2|max:255'
        ]);


        try {
            $updatedCategory = $this->categoryRepository->update($request->id, [
                'name' => $request->name,
                'slug' => $request->slug
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError('دسته بندی بروزرسانی نشد');
        }

        return $this->respondSuccess('دسته بندی بروز رسانی شد', [
            'name'=> $updatedCategory->getName(),
            'slug'=> $updatedCategory->getSlug()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        if (!$this->categoryRepository->find($request->id)) {
            return $this->respondNotFound('دسته بندی وجود ندارد');
        }

        if (!$this->categoryRepository->delete($request->id)) {
            return $this->respondInternalError('دسته بندی حذف نشد');
        }

        return $this->respondSuccess('دسته بندی حذف شد');
    }
}