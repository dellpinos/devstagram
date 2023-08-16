<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }
    public function store(Request $request)
    {
        $request->request->add(['username' => Str::slug($request->username)]);

        if($request->passwordNuevo) {

            if(!auth()->attempt($request->only('email', 'password'))) {
                return back()->with('mensaje', 'Invalid username or password.');
            }
        }
        $this->validate($request, [
            'username' => ["required", "unique:users,username,".auth()->user()->id, "min:3", "max:20", "not_in:editar-perfil,login,logout,register,posts,imagenes"],
            'email' => ["required", "unique:users,email,".auth()->user()->id, "email", "max:60"],
            'passwordNuevo' => 'min:6|nullable'
        ]);

        if ($request->imagen) {

            $imagen = $request->file('imagen');
            $nombreImagen = Str::uuid() . "." . $imagen->extension();
            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000);
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        // Guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->password = $request->passwordNuevo ?? auth()->user()->password;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
        $usuario->save();

        // Redireccionar
        return redirect()->route('posts.index', $usuario->username);
    }
}
