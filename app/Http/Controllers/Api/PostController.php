<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->paginate(5);

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
            return response()->json($validate->errors(), 422);
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

    //method update
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $post = Post::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload gambar baru
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //menghapus gambar lama
            Storage::delete('public/posts/' .basename($post->image));

            //update post dengan gambar baru
            $post->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        } else {

            //update post without image
            $post->update([
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        }

        //return response
        return new PostResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        Storage::delete('public/posts/' . basename($post->image));

        $post->delete();
        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}
