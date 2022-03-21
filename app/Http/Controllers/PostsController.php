<?php

namespace App\Http\Controllers;

use App\Models\postLikes;
use App\Models\posts;
use App\Models\User;
use App\Notifications\NewPostNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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

        // $posts = posts::select(
        //     "posts.id", 
        //     "posts.author",
        //     "posts.topic", 
        //     "posts.image", 
        //     "posts.description", 
        //     "posts.likes as total_likes_count",
        //     "posts.created_at", 
        //     "users.username as author"
        // )
        // ->join("users", "users.id", "=", "posts.author")
        // ->with('postLikeUsers')
        // ->orderBy('created_at', 'Desc')
        // ->paginate(10);

        return \response()->json($posts);
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
        $filename = '';

        $uploadedFile = $request->file('image');
        if ($uploadedFile) {
            $filename = time().$uploadedFile->getClientOriginalName();

            Storage::disk('local')->putFileAs(
                'post-images/'.$filename,
                $uploadedFile,
                $filename
            );
        }

        if($validatedData) {
            $post = posts::create([
                'author' => $user_id,
                'description' => $validatedData['description'],
                'topic' => $validatedData['topic'],
                'image' => $filename ? $filename : '',

            ]);

            $users = User::where('id', '!=',$user_id)->get();
            Notification::send($users, new NewPostNotification($post));

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
        $username = Auth::user()->username;

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
                'username' => $username,
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

        // check if the user has actually liked this post...
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function destroy($post)
    {
        $user_id = Auth::id();

        $post = posts::where('id',$post)->where('author', $user_id);

        $fetch_post = $post->value('image');
        
        $delete_post = $post->delete();

        if ($delete_post) {
            Storage::disk('local')->deleteDirectory(
                'post-images/'.$fetch_post
            );

            return response()->json([
                'message' => 'Post deleted successfully',
                'status_code' => Response::HTTP_OK
            ]);
        }
    }
}
