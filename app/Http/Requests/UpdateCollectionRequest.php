<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method == 'PUT')
        {
            return [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'targetAmount' => 'required|numeric',
                'link' => 'required|string|max:255'
            ];
        } else {
            return [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'targetAmount' => 'sometimes|required|numeric',
                'link' => 'sometimes|required|string|max:255'
            ];
        }        
    }

    protected function prepareForValidation()
    {
        if ($this->has('targetAmount'))
        {
            $this->merge([
                'target_amount' => $this->targetAmount
            ]);
        }
    }
}
