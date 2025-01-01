<?php

namespace App\Http\Controllers\Api\V1\ProductService;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PictureController;
use App\Http\Requests\ProductService\StorePlatformRequest;
use App\Http\Resources\ProductService\PlatformCollection;
use App\Http\Resources\ProductService\PlatformResource;
use App\Models\MediaStorage\MediaStorage;
use App\Models\ProductService\Platform;
use App\Models\ProductService\PlatformType;
use App\Utils\ApiResponse;
use App\Utils\UuidHelper;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PlatformApiController extends Controller
{
    /**
     *  Display a listing of the resource.
     *
     *  @param Request $request
     *  @param string $id
     *  @return PlatformCollection
     *  @throws NotFoundException
     */
    public function index(Request $request, string $id)
    {
        $platformType = PlatformType::query();
        if(UuidHelper::isUuid($id))
            $platformType->where('id', $id);
        else
            $platformType->where('slug', $id);

        try {
            $platformType = $platformType->firstOrFail();
            $platforms = Platform::with(['image', 'banner'])
                ->where('type_id', $platformType->id)
                ->paginate($request->get('perPage', 10));
            return new PlatformCollection($platforms);
        }
        catch (\Exception $exception)
        {
            throw new NotFoundException([
                'messages' => 'Not Found',
                'code' => 1001,
                'details' => 'The requested platform type ID does not exist in the database.',
            ]);
        }
    }

    /**
     *  Store a newly created resource in storage.
     *
     * @param StorePlatformRequest $request
     * @return Response|PlatformResource
     * @throws BadRequestException
     */
    public function store(StorePlatformRequest $request): Response|PlatformResource
    {
        if (Auth::check() && Auth::user()->role == 'administrator')
        {
            $imageId = PictureController::load($request->file('image'), 'platform-icon');
            $bannerId = PictureController::load($request->file('banner'), 'banner');
            $platform = Platform::create(array(
                'id' => UuidHelper::generateUuid(),
                'slug' => $request->get('slug'),
                'title' => $request->get('title'),
                'parent_id' => $request->get('parent_id'),
                'image_id' => $imageId->getData()->data->id,
                'banner_id' => $bannerId->getData()->data->id,
                'type_id' => $request->get('type_id'),
            ));
            return new PlatformResource($platform);
        }
        else
            throw new UnauthorizedException([]);
    }

    /**
     *  Display the specified resource.
     *
     *  @param string $id
     *  @return PlatformResource
     *  @throws NotFoundException
     */
    public function show(string $id): PlatformResource
    {
        $field = 'slug';
        if (UuidHelper::isUuid($id))
            $field = 'id';

        try {
            $platform = Platform::with(['image', 'banner', 'categories'])
                ->where($field, $id)
                ->first();
            return new PlatformResource($platform);
        }
        catch (\Exception $exception)
        {
            $errorInfo = [
                'details' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ];
            throw new NotFoundException($errorInfo);
        }
    }

    /**
     *  Update the specified resource in storage.
     *
     *  @param Request $request
     *  @param Platform $platform
     *  @return PlatformResource
     */
    public function update(Request $request, Platform $platform) : PlatformResource
    {
        $request->validate([
            'slug' => 'required|string|unique:platforms,slug,' . $platform->id . '|max:100',
            'title' => 'required|string|max:150',
            'image_id' => 'nullable|uuid|exists:media_storage,id',
            'banner_id' => 'nullable|uuid|exists:media_storage,id',
            'type_id' => 'required|uuid|exists:platform_types,id',
        ]);

        $platform->update($request->all());
        return new PlatformResource($platform);
    }

    /**
     *  Remove the specified resource from storage.
     *
     *  @param string $id
     *  @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        if (Auth::check() && Auth::user()->role == 'administrator')
        {
            $searchField = 'slug';
            if(UuidHelper::isUuid($id))
                $searchField = 'id';

            try {
                $platform = Platform::where($searchField, $id)
                    ->first();

                Platform::where($searchField, $id)
                    ->delete();

                PictureController::remove($platform->image_id);
                PictureController::remove($platform->banner_id);
                return ApiResponse::deleted();
            }  catch (\Exception $exception)
            {
                throw new NotFoundException(['code' => 404, 'details' => 'Not Found']);
            }
        }
        else
            throw new UnauthorizedException([]);

    }
}
