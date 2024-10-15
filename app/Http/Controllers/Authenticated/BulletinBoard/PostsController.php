<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use Illuminate\Support\Facades\DB;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        // 初期クエリを定義
        $query = Post::with('user', 'postComments', 'subCategories')->withCount('likes', 'comments');

        // ①キーワード検索
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('post_title', 'like', '%' . $keyword . '%')
                    ->orWhere('post', 'like', '%' . $keyword . '%');
            });
        }

        // ④サブカテゴリーの完全一致
        if (!empty($request->sub_category_id)) {
            $subCategoryId = $request->sub_category_id;
            $query->whereHas('subCategories', function ($q) use ($subCategoryId) {
                $q->where('sub_category_id', $subCategoryId);
            });
        }

        // dd($request);

        // ②いいねした投稿のみ表示
        if (!empty($request->like_posts)) {
            $likes = Auth::user()->likePostId()->pluck('like_post_id');
            $query->whereIn('id', $likes);
        }

        // ③自分の投稿のみ表示
        if (!empty($request->my_posts)) {
            $query->where('user_id', Auth::id());
        }

        // クエリを実行して結果を取得
        $posts = $query->get();

        $categories = MainCategory::with('subCategories')->get();
        // dd($categories);
        $like = new Like;
        $post_comment = new Post;

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $main_categories = MainCategory::with('subCategories')->get(); // サブカテゴリーも含めて取得
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }


    public function postCreate(PostFormRequest $request)
    {

        DB::beginTransaction();

        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);

        $post->subCategories()->attach($request->post_category_id);

        DB::commit();

        return redirect()->route('post.show');
    }

    public function postEdit(Request $request)
    {
        $request->validate([
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:5000',
        ]);

        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    //メインカテゴリー
    public function mainCategoryCreate(Request $request)
    {
        MainCategory::create([
            'main_category' => $request->main_category_name
        ]);
        return redirect()->route('post.input');
    }
    //サブカテゴリー
    public function subCategoryCreate(Request $request)
    {
        SubCategory::create([
            'sub_category' => $request->sub_category_name,
            'main_category_id' => $request->main_category_id // メインカテゴリーのIDを設定
        ]);
        return redirect()->route('post.input');
    }

    public function commentCreate(Request $request)
    {

        $request->validate([
            'comment' => 'required|string|max:250',
        ]);

        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
            ->where('like_post_id', $post_id)
            ->delete();

        return response()->json();
    }
}
