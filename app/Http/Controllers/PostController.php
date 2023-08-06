<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }
    public function index(User $user)
    {

        $posts = Post::where('user_id', $user->id)->paginate(20);

        return view('dashboard', [
            'user' => $user,
            'posts' => $posts

        ]);
    }
    public function create()
    {
        return view('posts.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id
        ]);



        return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post)
    {

        if($post->user_id !== $user->id) { // Si cambio el username puedo acceder a publicaciones de otros usuarios, este código comprueba que el "post" y el "user" consultados a la base de datos se correspondan
            return redirect()->route('register'); // redirijo a register porque aun no existe una ruta principal o #404

        }
        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]);
    }

    public function destroy(Post $post)
    {
        // Eliminar registro
        $this->authorize('delete', $post);
        $post->delete();

        // Eliminar imagen
        $imagen_path = public_path('uploads/' . $post->imagen);

        if(File::exists($imagen_path)) {
            unlink($imagen_path);
        }


        return redirect()->route('posts.index', auth()->user()->username);
    }

}
