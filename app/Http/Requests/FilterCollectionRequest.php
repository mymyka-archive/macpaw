<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class FilterCollectionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'filters' => 'required|array',
            'filters.*.name' => 'required|string|in:' . $this->getAllowedNames(),
            'filters.*.order' => 'sometimes|required|string|in:ASC,DESC',
        ];
    }

    public function getAllowedNames(): string
    {
        return implode(',', [
            'sumLeft',
            'activeCollection',
        ]);
    }
}
