<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OpeningsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'monday1' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'monday4' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'monday3' => 'nullable|exists:openings,openings',
            'monday2' => 'nullable|exists:openings,openings',
            'tuesday1' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'tuesday4' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'tuesday3' => 'nullable|exists:openings,openings',
            'tuesday2' => 'nullable|exists:openings,openings',
            'wednesday1' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'wednesday4' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'wednesday3' => 'nullable|exists:openings,openings',
            'wednesday2' => 'nullable|exists:openings,openings',
            'thursday1' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'thursday4' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'thursday3' => 'nullable|exists:openings,openings',
            'thursday2' => 'nullable|exists:openings,openings',
            'friday1' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'friday4' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'friday3' => 'nullable|exists:openings,openings',
            'friday2' => 'nullable|exists:openings,openings',
            'saturday1' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'saturday4' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'saturday3' => 'nullable|exists:openings,openings',
            'saturday2' => 'nullable|exists:openings,openings',
            'sunday1' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'sunday4' => ['required', 'exists:openings,openings',Rule::notIn(['Aucun'])],
            'sunday3' => 'nullable|exists:openings,openings',
            'sunday2' => 'nullable|exists:openings,openings',
        ];
    }
}
