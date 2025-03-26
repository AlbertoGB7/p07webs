<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Usuari;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RecuperacioController extends Controller
{
    public function mostrarFormulariCorreu()
    {
        return view('enviar_correu');
    }
    
    public function enviarCorreu(Request $request)
    {
        $request->validate([
            'correu' => 'required|email',
        ]);
        
        $correu = $request->input('correu');
        
        // Verificar si el correu existeix a la base de dades
        $usuari = Usuari::obtenirPerCorreu($correu);
        
        if (!$usuari) {
            return redirect('/enviar_correu')
                ->with('missatge', 'No hi ha cap usuari registrat amb aquest correu.');
        }
        
        // Comprovar si l'usuari té autenticació social
        if ($usuari->aut_social === 'si') {
            return redirect('/enviar_correu')
                ->with('missatge', 'Correu no enviat ja que no es pot amb autenticació social.');
        }
        
        // Generar un token
        $token = Str::random(32);
        $usuari->guardarTokenRecuperacio($token);
        
        // Enviar el correu amb PHPMailer
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'a.gonzalez7@sapalomera.cat';
            $mail->Password = 'wrjb rfpy gpbf nhsa';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('a.gonzalez7@sapalomera.cat', 'Administrador');
            $mail->addAddress($correu);
    
            $mail->isHTML(true);
            $mail->Subject = 'Recuperar contrasenya';
            $mail->Body = "Prem el següent link per restablir la contrasenya: 
            <a href='" . url('/restablir_contrasenya/' . $token) . "'>Restablir contrasenya</a>";
    
            $mail->send();
            return redirect('/enviar_correu')
                ->with('missatge_exit', 'Correu enviat correctament. Mira el teu correu per restablir la contrasenya.');
        } catch (Exception $e) {
            return redirect('/enviar_correu')
                ->with('missatge', 'No s\'ha pogut enviar el correu. Error: ' . $mail->ErrorInfo);
        }
    }
    
    public function mostrarFormulariRestablir($token)
    {
        // Verificar que el token existe y es válido antes de mostrar el formulario
        $usuari = Usuari::obtenirPerTokenRecuperacio($token);
        
        if (!$usuari) {
            return redirect('/enviar_correu')
                ->with('missatge', 'Token invàlid o caducat. Sol·licita un nou enllaç de restabliment.');
        }
        
        return view('restablir_contrasenya', ['token' => $token]);
    }
    
    public function restablirContrasenya(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'passnova' => 'required',
            'rptpass' => 'required|same:passnova',
        ]);
        
        $token = $request->input('token');
        $novaContrasenya = $request->input('passnova');
        
        // Verificar que el token sigui vàlid
        $usuari = Usuari::obtenirPerTokenRecuperacio($token);
        
        if (!$usuari) {
            return redirect('/restablir_contrasenya/' . $token)
                ->with('missatge', 'Token invàlid o caducat.');
        }
        
        // Verificar que la contrasenya sigui segura
        if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $novaContrasenya)) {
            return redirect('/restablir_contrasenya/' . $token)
                ->with('missatge', 'La contrasenya ha de contenir 8 caràcters, una mayúscula, un número i un símbol.');
        }
        
        // Actualitzar la contrasenya
        $usuari->actualitzarContrasenya(Hash::make($novaContrasenya));
        
        // Eliminar el token de recuperació
        $usuari->eliminarTokenRecuperacio();
        
        return redirect('/login')
            ->with('missatge_exit', 'Contrasenya restablerta correctament. Ara pots iniciar sessió amb la nova contrasenya.');
    }
}