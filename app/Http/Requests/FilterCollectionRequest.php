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
            'name' => "required|string|max:255|in:{$this->getAllowedNames()}",
            'order' => 'required|string|max:255|in:ASC,DESC',
            'sortField' => 'sometimes|required|string|max:255',
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
