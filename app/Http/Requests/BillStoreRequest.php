<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BillStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
    return $this->user() !== null; // any authenticated user may create a bill; restrict via route middleware if needed
    }

    public function rules(): array
    {
        return [
            'tenant_id' => ['required','exists:tenants,id'],
            'current_reading' => ['required','integer','min:0'],
            'month' => ['nullable','string'],
            'unit_price' => ['nullable','integer','min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'tenant_id.required' => 'Select a tenant.',
            'tenant_id.exists' => 'Selected tenant is invalid.',
            'current_reading.required' => 'Enter current meter reading.',
        ];
    }
}
