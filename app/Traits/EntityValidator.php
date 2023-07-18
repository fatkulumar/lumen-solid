<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait EntityValidator
{
    public static function validate($input, $rules)
    {
        $messages = [
            'required' => 'Isian :attribute harus diisi',
            'mimes' => 'Isian file untuk :attribute harus bertipe: :values.',
            'max'   => [
                'numeric' => 'Isian :attribute tidak boleh lebih besar dari :max.',
                'file'    => 'Isian :attribute tidak boleh lebih besar dari :max kilobytes.',
                'string'  => 'Isian :attribute tidak boleh lebih besar dari :max characters.',
                'array'   => 'Isian :attribute tidak boleh lebih besar dari :max items.',
            ],
            'numeric'   => 'Isian :attribute hanya boleh diisi angka.',
            'min'   => [
                'numeric' => 'Isian :attribute harus lebih besar / sama dengan :min.',
                'file'    => 'Isian :attribute harus lebih besar / sama dengan  :min kilobytes.',
                'string'  => 'Isian :attribute harus lebih besar / sama dengan  :min characters.',
                'array'   => 'Isian :attribute harus lebih besar / sama dengan  :min items.',
            ],
            'alpha_dash' => 'Isian :attribute hanya dapat menerima input abjad, angka dan dash (-)',
            'date'  => 'Isian :attribute bukan tanggal yang valid.',
            'integer'  => 'Isian :attribute harus integer.',
            'between' => 'Isian :attribute harus diantara :min dan :max digit',
            'regex' => 'Isian :attribute tidak sesuai format',
            'password.regex' => 'Password merupakan kombinasi huruf besar dan kecil, angka, dan minimal terdapat enam karakter'
        ];

        $validator = Validator::make($input, $rules, $messages);
        return $validator;
    }
}
