<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;

class Subjects extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'subject'
    ];


    //基本的な型は第一引数は相手のモデル、第二はテーブル名、第三自分のIDが入る場所　、第四　相手のIDがはいるカラムを指定
    //省略可能な場合もあり。laravel公式なドキュメントあり
    public function users()
    {
        return $this->belongsToMany('App\Models\Users\User', 'subject_users', 'subject_id', 'user_id'); // リレーションの定義
    }
}
