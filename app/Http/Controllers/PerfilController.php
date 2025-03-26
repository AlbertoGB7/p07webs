<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class PerfilController extends Controller
{
    /*
     * Mostra el formulari per modificar les dades del perfil
     */
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
    
    /*
     * Processa l'actualització del perfil (nom i imatge)
     */
    public function actualitzarPerfil(Request $request)
    {
        $usuari = session('usuari');
        
        if (!$usuari) {
            return redirect('/login');
        }
        
        $dadesUsuari = Usuari::where('usuari', $usuari)->first();
        
        // Verificar si el nom ja existeix (si ha canviat)
        $nouNom = $request->input('nou_nom');
        if ($nouNom !== $dadesUsuari->usuari) {
            $existeUsuari = Usuari::where('usuari', $nouNom)->first();
            if ($existeUsuari) {
                Session::flash('missatge', "El nom d'usuari ja està en ús.");
                return redirect('/modificar_perfil');
            }
        }
        
        // Actualitzar el nom d'usuari
        $dadesUsuari->usuari = $nouNom;
        
        // Processar la imatge si s'ha proporcionat una nova
        if ($request->hasFile('nova_imatge')) {
            $imatge = $request->file('nova_imatge');
            
            // Validar el tipus d'arxiu
            $tipus = $imatge->getClientOriginalExtension();
            $tipusValids = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array(strtolower($tipus), $tipusValids)) {
                Session::flash('missatge', "El format de la imatge no és vàlid. Utilitzeu .jpg, .jpeg, .png o .webp.");
                return redirect('/modificar_perfil');
            }
            
            // Validar mida de l'arxiu (màxim 2MB)
            if ($imatge->getSize() > 2097152) {
                Session::flash('missatge', "La imatge és massa gran. Màxim 2MB.");
                return redirect('/modificar_perfil');
            }
            
            // Eliminar imatge anterior si existeix i no és la per defecte
            if ($dadesUsuari->imatge && !str_contains($dadesUsuari->imatge, 'def_user.jpeg')) {
                $oldImagePath = public_path($dadesUsuari->imatge);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            
            // Guardar nova imatge
            $novaImatgeNom = time() . '_' . $imatge->getClientOriginalName();
            $imatge->move(public_path('Imatges/perfil'), $novaImatgeNom);
            $dadesUsuari->imatge = 'Imatges/perfil/' . $novaImatgeNom;
        }
        
        $dadesUsuari->save();
        
        // Actualitzar nom a la sessió
        Session::put('usuari', $nouNom);
        
        Session::flash('missatge_exit', "Perfil actualitzat correctament!");
        return redirect('/modificar_perfil');
    }
}