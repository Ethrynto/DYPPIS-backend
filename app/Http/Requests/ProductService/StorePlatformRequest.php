<?php

namespace App\Http\Requests\ProductService;

use App\Utils\ErrorMessages;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePlatformRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', 'unique:platforms,slug'],
            'title' => ['required', 'string', 'max:255', 'unique:platforms,title'],
            'parent_id' => ['nullable', 'string', 'exists:platforms,id'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            'banner' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            'type_id' => ['required', 'string', 'exists:platform_types,id'],
        ];
    }

    public function messages(): array
    {
        return [
            /* slug field */
            'slug.required' => ErrorMessages::generate('field.required', ['field' => 'Slug']),
            'slug.string' => ErrorMessages::generate('field.string', ['field' => 'Slug']),
            'slug.unique' => ErrorMessages::generate('field.unique', ['field' => 'Slug']),
            'slug.max' => ErrorMessages::generate('field.max', ['count' => '255']),

            /* title field */
            'title.required' => ErrorMessages::generate('field.required', ['field' => 'Title']),
            'title.string' => ErrorMessages::generate('field.string', ['field' => 'Title']),
            'title.unique' => ErrorMessages::generate('field.unique', ['field' => 'Title']),
            'title.max' => ErrorMessages::generate('field.max', ['count' => '255']),

            /* icon field  */
            'image.required' => ErrorMessages::generate('field.required', ['field' => 'Icon']),
            'image.file' => ErrorMessages::generate('field.file', ['field' => 'Icon']),
            'image.mimes' => ErrorMessages::generate('field.mimes', ['mimes' => 'jpeg, jpg, png, svg']),
            'image.max' => ErrorMessages::generate('file.max', ['count' => '5']),

            /* banner field  */
            'banner.file' => ErrorMessages::generate('field.file', ['field' => 'Banner']),
            'banner.mimes' => ErrorMessages::generate('field.mimes', ['mimes' => 'jpeg, jpg, png, svg']),
            'banner.max' => ErrorMessages::generate('file.max', ['count' => '5']),

            /* category_id field */
            'type_id.required' => ErrorMessages::generate('field.required', ['field' => 'Type']),
            'type_id.exists' => ErrorMessages::generate('field.exists'),
        ];
    }
}
