<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterBulletinRequest extends FormRequest
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
            'eleve_id' => 'nullable|exists:eleves,id',
            'periode' => 'nullable|string|max:10',
            'annee' => 'nullable|digits:4',
        ];
    }

    public function messages(): array
    {
        return [
            'eleve_id.exists' => 'L\'élève spécifié n\'existe pas.',
            'periode.max' => 'La période ne doit pas dépasser 10 caractères.',
            'annee.digits' => 'L\'année doit contenir 4 chiffres.',
        ];
    }
}
