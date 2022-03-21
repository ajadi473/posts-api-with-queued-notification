<?php

namespace App\Http\Controllers;

use App\Models\postLikes;
use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = posts::orderBy('created_at', 'Desc')->with('postLikeUsers')->paginate(10);

        return \response()->json($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePost(Request $request)
    {

        $validatedData = $request->validate([
            'image' => 'sometimes',
            'description' => 'required|string',
            'topic' => 'required|string'
        ]);

        $user_id = Auth::id();
        $author = Auth::user()->name;

        if($validatedData) {
            $post = posts::create([
                'author' => $user_id,
                'description' => $validatedData['description'],
                'topic' => $validatedData['topic'],
                // 'image' => $this->faker->imageUrl(),

            ]);

            return response()->json([
                'message' => 'Post created successfully',
                'post' => $post,
                'status_code' => Response::HTTP_OK
            ]);
        }
    }


    public function likePost(Request $request, $id)
    {
        $user_id = Auth::id();

        // check if the user has already liked this post...
        $check_post = postLikes::where('post_id', $id)->where('user_id', $user_id)->get();
        if (count($check_post) > 0) {
            return response()->json([
                'message' => 'You aready liked this post',
                'status_code' => Response::HTTP_NOT_ACCEPTABLE
            ]);
        } else {

            $post_like = postLikes::create([
                'post_id' => $id, 
                'user_id' => $user_id
            ]);

            if ($post_like)
                $post = posts::where('id', $id)->increment('likes');
            
            return response()->json([
                'message' => 'Post liked successfully',
                'likes' => $post,
                'status_code' => Response::HTTP_OK
            ]);
        }


    }

    public function unlikePost(Request $request, $id)
    {
        $user_id = Auth::id();

        // check if thee user has actually liked this post...
        $check_post = postLikes::where('post_id', $id)->where('user_id', $user_id)->get();
        if (count($check_post) > 0) {

            $post_like = postLikes::where('post_id',$id)->where('user_id', $user_id)->delete();

            if ($post_like)
                $post = posts::where('id', $id)->decrement('likes');
            
            return response()->json([
                'message' => 'Post unliked successfully',
                'likes' => $post,
                'status_code' => Response::HTTP_OK
            ]);
        } else {
            return response()->json([
                'message' => 'Watchya doing!!!',
                'status_code' => Response::HTTP_BAD_REQUEST
            ]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function showPostLikes($id)
    {
        // $post_like = postLikes::with('postLikeUsers')->where('post_id',$id);
        $post_like = postLikes::where('post_id',$id)->with('postLikeUsers')->get();

        return response()->json([
            'message' => 'All post likes',
            'likes' => $post_like,
            'status_code' => Response::HTTP_OK
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function edit(posts $posts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, posts $posts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function destroy(posts $posts)
    {
        //
    }
}
