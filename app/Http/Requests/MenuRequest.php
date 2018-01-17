<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'id' => 'numeric',
                    'name' => 'required',
                    'quantity' => 'required|integer',
                    'category_id' => 'required_without:category|exists:menu_categories,id',
                    'category' => 'required_without:category_id|exists:menu_categories,category',
                    'price' => 'required|numeric|min:2',
                    'HHPrice' => 'numeric|min:2',
                    'tax' => 'required|numeric|exists:taxes,per_cent',
                    'alcohol' => 'required|boolean',
                    'ingredients' => 'required|string',
                    'availability' => 'required|boolean'
                ];
            }
            case 'PUT': {
                return [
                    'id' => 'numeric',
                    'name' => 'required',
                    'quantity' => 'required|integer',
                    'category_id' => 'required_without:category|exists:menu_categories,id',
                    'category' => 'required_without:category_id|exists:menu_categories,category',
                    'price' => 'required|numeric|min:1',
                    'HHPrice' => 'numeric|min:1',
                    'tax' => 'required|numeric|exists:taxes,per_cent',
                    'alcohol' => 'required|boolean',
                    'ingredients' => 'required|string',
                    'availability' => 'required|boolean'
                ];
            }
            case 'PATCH':
            default:
                break;
        }
    }

}
