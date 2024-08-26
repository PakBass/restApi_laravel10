<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->get();

        return new PostResource(true, 'List Data Posts', $posts);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'title'     => 'required',
            'content'   => 'required'
        ], [
            'image.required'    => 'Gambar harus diisi.',
            'image.image'       => 'File yang diunggah harus berupa gambar.',
            'image.mimes'       => 'Ekstensi gambar yang diperbolehkan hanya PNG, JPG, dan JPEG.',
            'image.max'         => 'Ukuran gambar maksimal 2MB.',
            'title.required'    => 'Judul harus diisi.',
            'content.required'  => 'Konten harus diisi.'
        ]);

        if($validate->fails()) {
            return response()->json($validate->errors()->all(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //create post
        $post = Post::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
        ]);
        
        return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    public function show($id)
    {
        $post = Post::find($id);

        return new PostResource(true, 'Detail Data Post', $post);
    }
}
