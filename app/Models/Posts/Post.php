<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories\SubCategory;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments()
    {
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories()
    {
        // リレーションの定義
        return $this->belongsToMany(SubCategory::class, 'post_sub_category', 'post_id', 'sub_category_id');
    }

    //いいね数
    public function likes()
    {
        return $this->hasMany('App\Models\Posts\Like', 'like_post_id');
    }

    // コメント数
    public function comments()
    {
        return $this->hasMany('App\Models\Posts\PostComment', 'post_id');
    }
}
