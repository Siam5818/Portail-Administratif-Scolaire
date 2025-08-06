<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateBulletinRequest extends FormRequest
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
            'eleve_id' => 'required|exists:eleves,id',
            'periode' => 'required|string|max:10',
            'annee' => ['required', 'regex:/^\d{4}-\d{4}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'eleve_id.required' => 'L\'identifiant de l\'élève est requis.',
            'eleve_id.exists' => 'L\'élève spécifié n\'existe pas.',
            'periode.required' => 'La période est obligatoire.',
            'annee.required' => 'L\'année scolaire est obligatoire.',
            'annee.regex' => 'Le format de l\'année doit être "YYYY-YYYY", comme 2025-2026.',
        ];
    }
}
