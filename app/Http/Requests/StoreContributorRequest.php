<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContributorRequest extends FormRequest
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
            'userName' => 'required|string|max:255',
            'collectionId' => ['required', 'integer', 'exists:collections,id'],
            'amount' => 'required|numeric'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_name' => $this->userName,
            'collection_id' => $this->collectionId,
            'amount' => $this->amount
        ]);
    }
}
