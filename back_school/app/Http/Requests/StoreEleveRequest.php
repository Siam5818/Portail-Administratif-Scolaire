<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Arr;

class StoreEleveRequest extends FormRequest
{
    protected ?string $tuteurEmail = null;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prépare les données avant validation
     */
    protected function prepareForValidation(): void
    {
        // Récupère l'email du tuteur même si les données ne sont pas encore validées
        $this->tuteurEmail = Arr::get($this->all(), 'tuteur.email');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userExists = $this->tuteurEmail
            ? User::where('email', $this->tuteurEmail)->exists()
            : false;

        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'date_naissance' => 'nullable|date',
            'classe_id' => 'nullable|exists:classes,id',
            'document_justificatif' => 'nullable|string',
            'tuteur.nom' => 'required|string',
            'tuteur.prenom' => 'required|string',
            'tuteur.email' => [
                'required',
                'email',
                $userExists
                    ? Rule::exists('users', 'email')
                    : Rule::unique('users', 'email'),
            ],
            'tuteur.telephone' => 'nullable|string',
            'tuteur.profession' => 'nullable|string',
        ];
    }
}
