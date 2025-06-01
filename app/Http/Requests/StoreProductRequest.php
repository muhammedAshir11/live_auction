<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Product; // Import Product model if you're passing it to the policy

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->can('create', Product::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'starting_bid' => ['required', 'numeric', 'min:0.01'],
            'bid_end_time' => ['required', 'date', 'after:now'],
            'id' => ['nullable'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'bid_end_time.after' => 'The bid end time must be a date and time after the current moment.',
        ];
    }
}
