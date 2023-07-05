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

        return $this->respondCreated('دسته بندی ایجاد شد',[
            'name' => $createdCategory->getName(),
            'slug' => $createdCategory->getSlug()
        ]);
    }
}
