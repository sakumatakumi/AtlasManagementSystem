<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterFormRequest extends FormRequest
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
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|kana|max:30',
            'under_name_kana' => 'required|string|kana|max:30',
            'mail_address' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($this->id),],
            'sex' => 'required|in:1,2,3',
            'birth_day' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
            'role' => 'required|in:1,2,3,4|',
            'password' => 'required|min:8|max:30|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'over_name_kana.kana' => '名前のカナは必須項目です。',
            'under_name_kana.kana' => '名前のカナは必須項目です。',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->filled(['old_year', 'old_month', 'old_day'])) {
            $this->merge([
                'birth_day' => "{$this->old_year}-{$this->old_month}-{$this->old_day}",
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('birth_day') && !checkdate($this->old_month, $this->old_day, $this->old_year)) {
                $validator->errors()->add('birth_day', '正しい日付を入力してください。');
            }
        });
    }
}
