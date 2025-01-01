<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Models\MediaStorage\MediaStorage;
use App\Models\MediaStorage\MediaStorageCategory;
use App\Utils\ApiResponse;
use App\Utils\UuidHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PictureController
{
    /**
     *  @param mixed $imageFile Picture file
     *  @param string|null $categoryId The category id or slug for load picture
     *  @return mixed
     *  @throws BadRequestException
     */
    public static function load(mixed $imageFile, string|null $categoryId = null): mixed
    {
        $category = null;
        if (UuidHelper::isUuid($categoryId) && $categoryId !== null)
            $category = MediaStorageCategory::findOrFail($categoryId);
        else
            $category = MediaStorageCategory::where('slug', $categoryId ?? 'product-background')
                ->firstOrFail();

        $fileInfo = [
            'id' => Str::uuid(),
            'file_name' => Str::uuid() . '.' . $imageFile->getClientOriginalExtension(),
            'file_type' => $imageFile->getMimeType(),
            'file_size' => $imageFile->getSize(),
            'category_id' => $category->id,
            'created_at' => now(),
        ];

        try {
            Storage::disk('public')->putFileAs($category->path, $imageFile, $fileInfo['file_name']);
            MediaStorage::insert($fileInfo);
            return ApiResponse::created($fileInfo);
        }
        catch (\Exception $e) {
            $errorInfo = [
                'details' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
            throw new BadRequestException($errorInfo);
        }
    }

    /**
     *  Remove the specified resource from storage.
     *
     *  @param string $id
     *  @return JsonResponse
     */
    public static function remove(string $id): JsonResponse
    {
        $fileInfo = MediaStorage::where('id', $id)
            ->first(['file_name', 'category_id']);
        $categoryPath = MediaStorageCategory::where('id', $fileInfo->category_id)
            ->pluck('path')
            ->first();
        Storage::disk('public')->delete($categoryPath . '/' . $fileInfo->file_name);
        MediaStorage::destroy($id);

        return ApiResponse::deleted();
    }
}
