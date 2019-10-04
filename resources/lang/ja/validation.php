<?php

declare(strict_types=1);

return [
    'required' => '「:attribute」を入力してください',
    'min' => [
        'numeric' => '「:attribute」には「:min」以上の値を入力してください',
    ],
    'max' => [
        'numeric' => '「:attribute」には「:max」以下の値を入力してください',
    ],
    'between' => [
        'numeric' => '「:attribute」には「:min」以上「:max」以下の値を入力してください',
    ],

    'attributes' => [
        'name' => '名称',
        'openingBalance' => '開始残高',
        'debitAccountId' => '借方科目',
        'creditAccountId' => '貸方科目',
    ],
];
