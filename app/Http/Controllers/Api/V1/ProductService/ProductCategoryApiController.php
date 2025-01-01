<?php

namespace App\Http\Controllers\Api\V1\ProductService;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PictureController;
use App\Http\Requests\ProductService\StoreProductCategoryRequest;
use App\Http\Resources\ProductService\ProductCategoryAllCollection;
use App\Http\Resources\ProductService\ProductCategoryCollection;
use App\Models\MediaStorage\MediaStorage;
use App\Models\ProductService\Platform;
use App\Models\ProductService\ProductCategory;
use App\Utils\ApiResponse;
use App\Utils\UuidHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryApiController extends Controller
{
    /**
     *  Display a listing of the resource.
     *
     *  @param Request $request
     *  @param string|null $id
     *  @return ProductCategoryAllCollection|ProductCategoryCollection
     */
    public function index(Request $request, string $id = null): ProductCategoryAllCollection|ProductCategoryCollection
    {
        if($id)
        {
            $field = 'slug';
            if(UuidHelper::isUuid($id))
                $field = 'id';

            $platform = Platform::where($field, $id)
                ->firstOrFail();

            $productCategories = $platform->categories;
            return new ProductCategoryCollection($productCategories);
        }
        else
        {
            $productCategories = ProductCategory::paginate($request->get('perPage', 15));
            return new ProductCategoryAllCollection($productCategories);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        if (Auth::check() && Auth::user()->role == 'administrator')
        {
            $imageId = PictureController::load($request->file('image'), 'icon');
            $platform = Platform::create(array(
                'id' => UuidHelper::generateUuid(),
                'slug' => $request->get('slug'),
                'title' => $request->get('title'),
                'image_id' => $imageId->getData()->data->id,
                'is_published' => $request->get('is_published') ?? false,
            ));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $searchField = 'slug';
        if(UuidHelper::isUuid($id))
            $searchField = 'id';

        try {
            $productCategory = ProductCategory::where($searchField, $id)
                ->first();

            ProductCategory::where($searchField, $id)
                ->delete();

            MediaStorage::destroy($productCategory->image_id);
            return ApiResponse::deleted();
        } catch (\Exception $exception) {
            throw new NotFoundException(['code' => 404, 'details' => 'Not Found']);
        }
    }
}
