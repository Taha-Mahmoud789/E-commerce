<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoponRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'sometimes|required|string|unique:coupons,code,' . $this->route('id') . '|max:255',
            'discount' => 'sometimes|required|numeric',
            'discount_type' => 'sometimes|required|in:percentage,fixed',
            'expires_at' => 'nullable|date_format:Y-m-d',
            'is_active' => 'sometimes|required|boolean',
        ];
    }
}
