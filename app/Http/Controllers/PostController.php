<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\Image;
use App\Models\Post;
//use Illuminate\Support\Facades\DB;
use App\Services\Counter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    private $counter;

    public function __construct(Counter $counter)
    {
        $this->middleware('auth')->only([
            'create',
            'store',
            'edit',
            'update',
            'destroy'
        ]);

        $this->counter = $counter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        DB::connection()->enableQueryLog();
//
//        $posts = Post::with('comments')->get();
//
//        foreach($posts as $post) {
//            foreach($post->comments as $comment) {
//                echo $comment->content;
//            }
//        }
//
//        dd(DB::getQueryLog());


        return view(
            'posts.index',
            [
                'posts' => Post::latestWithRelations()->get()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $post = Post::create($validatedData);

        $hasFile = $request->hasFile('thumbnail');

        if ($hasFile) {
            $path = $request->file('thumbnail')->store('thumbnails');
            $post->image()->save(
                Image::make(['path' => $path])
            );
//            dump($file);
//            dump($file->getClientMimeType());
//            dump($file->getClientOriginalExtension());

//            $file->store('thumbnails');
//            Storage::disk('public')->putFile('thumbnails', $file);
//            $name1 = $file->storeAs('thumbnails', $post->id . '.' . $file->guessExtension());
//            $name2 = Storage::disk('local')->putFileAs('thumbnails', $file, $post->id . '.' . $file->guessExtension());
//
//            dump(Storage::url($name1));
//            dump(Storage::disk('local')->url($name2));
        }

        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        return view('posts.show', [
//            'post' => Post::with(['comments' => function($query) {
//                return $query->latest();
//            }])->findOrFail($id)
//        ]);

        $post = Cache::remember("post-{$id}", 60, function() use($id) {
            return Post::with('comments', 'tags', 'user', 'comments.user')->findOrFail($id);
        });

        return view('posts.show', [
            'post' => $post,
            'counter' => $this->counter->increment("post-{$id}", ['post'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize($post);

        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = Post::findOrFail($id);

//        if (Gate::denies('update-post', $post)) {
//            abort(403);
//        }
        $this->authorize($post);

        $validatedData = $request->validated();

        $post->fill($validatedData);

        $hasFile = $request->hasFile('thumbnail');

        if ($hasFile) {
            $path = $request->file('thumbnail')->store('thumbnails');

            if($post->image) {
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            } else {
                $post->image()->save(
                    Image::make(['path' => $path])
                );
            }

        }

        $post->save();

        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

//        if (Gate::denies('delete-post', $post)) {
//            abort(403);
//        }
        $this->authorize($post);

        $post->delete();

        //Post::destroy($id);

        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }
}
