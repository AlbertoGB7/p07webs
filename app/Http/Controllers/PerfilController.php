<?php

namespace App\Http\Controllers;

use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class PerfilController extends Controller
{
    public function mostrarFormulari()
    {
        $usuari = session('usuari');
        
        if (!$usuari) {
            return redirect('/login');
        }
        
        $dadesUsuari = Usuari::where('usuari', $usuari)->first();
        
        return view('modificar_perfil', [
            'dadesUsuari' => $dadesUsuari
        ]);
    }
    
    public function actualitzarPerfil(Request $request)
    {
        $usuari = session('usuari');
        
        if (!$usuari) {
            return redirect('/login');
        }
        
        $dadesUsuari = Usuari::where('usuari', $usuari)->first();
        
        // Verificar si el nuevo nombre ya existe (si cambió)
        $nouNom = $request->input('nou_nom');
        if ($nouNom !== $dadesUsuari->usuari) {
            $existeUsuari = Usuari::where('usuari', $nouNom)->first();
            if ($existeUsuari) {
                Session::flash('missatge', "El nom d'usuari ja està en ús.");
                return redirect('/modificar_perfil');
            }
        }
        
        // Actualizar el nombre de usuario
        $dadesUsuari->usuari = $nouNom;
        
        // Procesar la imagen si se proporcionó una nueva
        if ($request->hasFile('nova_imatge')) {
            $imatge = $request->file('nova_imatge');
            
            // Validar el tipo de archivo
            $tipus = $imatge->getClientOriginalExtension();
            $tipusValids = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array(strtolower($tipus), $tipusValids)) {
                Session::flash('missatge', "El format de la imatge no és vàlid. Utilitzeu .jpg, .jpeg, .png o .webp.");
                return redirect('/modificar_perfil');
            }
            
            // Validar tamaño del archivo (máximo 2MB)
            if ($imatge->getSize() > 2097152) {
                Session::flash('missatge', "La imatge és massa gran. Màxim 2MB.");
                return redirect('/modificar_perfil');
            }
            
            // Eliminar imagen anterior si existe y no es la por defecto
            if ($dadesUsuari->imatge && !str_contains($dadesUsuari->imatge, 'def_user.jpeg')) {
                $oldImagePath = public_path($dadesUsuari->imatge);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            
            // Guardar nueva imagen
            $novaImatgeNom = time() . '_' . $imatge->getClientOriginalName();
            $imatge->move(public_path('Imatges/perfil'), $novaImatgeNom);
            $dadesUsuari->imatge = 'Imatges/perfil/' . $novaImatgeNom;
        }
        
        $dadesUsuari->save();
        
        // Actualizar nombre en la sesión
        Session::put('usuari', $nouNom);
        
        Session::flash('missatge_exit', "Perfil actualitzat correctament!");
        return redirect('/modificar_perfil');
    }
}