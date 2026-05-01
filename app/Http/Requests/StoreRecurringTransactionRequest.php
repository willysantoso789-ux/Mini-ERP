<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecurringTransactionRequest extends FormRequest
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
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:1',
            'frequency' => 'required|in:daily,weekly,monthly',
            'next_processing_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:255',
        ];
    }
}
