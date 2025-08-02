<?php

namespace App\Http\Requests;

use App\Models\Enseignant;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEnseignantRequest extends FormRequest
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
        /** @var Request $this */
        $enseignantId = $this->route('enseignant');
        $userId = null;

        if ($enseignantId) {
            $enseignant = Enseignant::find($enseignantId);
            if ($enseignant && $enseignant->user) {
                $userId = $enseignant->user->id;
            }
        }
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'classe_id' => 'nullable|exists:classes,id',
            'specialite' => 'nullable|string|max:255',
        ];
    }
}
