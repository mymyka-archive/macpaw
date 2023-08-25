<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContributorRequest extends FormRequest
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
        $method = $this->getMethod();

        if ($method == 'PUT') {
            return [
                'userName' => 'required|string|max:255',
                'collectionId' => ['required', 'integer', 'exists:collections,id'],
                'amount' => 'required|numeric'
            ];
        } else {
            return [
                'userName' => 'sometimes|required|string|max:255',
                'collectionId' => ['sometimes', 'required', 'integer', 'exists:collections,id'],
                'amount' => 'sometimes|required|numeric'
            ];
        }
    }

    protected function prepareForValidation()
    {
        if ($this->has('userName')) {
            $this->merge([
                'user_name' => $this->userName
            ]);
        }

        if ($this->has('collectionId')) {
            $this->merge([
                'collection_id' => $this->collectionId
            ]);
        }
    }
}
