<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\Contact;
use Illuminate\Http\Request;
use App\User;
use App\Post;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

// $text = "\xF0\x9F\x92\xB0 Clausulazo!!\n"
// . "El <b>Zenit (pAdRoNe)</b> paga la clausula de " . '<a href="http://pesdb.net/pes2018/?id=7511">L. Messi</a>' . " que pertenecía al Borussia Dortmund (Angel_el_grande)\n"
// . "Desembolsa <b>220 (200+20) millones</b> de euros\n"
// . "<i>Presupuesto del Zenit: 25 mill.</i>\n"
// . "<i>Presupuesto del Borussia Dortmund: 275 mill.</i>";

// $text = "\xF0\x9F\x93\x8A Clasificación LIGA NORTE\n"
// . "<pre>1.- Botafogo  - 12 puntos</pre>\n"
// . "<pre>2.- Ajax      -  9 puntos</pre>\n"
// . "<pre>3.- Liverpool -  7 puntos</pre>\n"
// . "...";

// $text = "\xE2\x9A\xBD Resultado registrado\n"
// . "<pre>LIGA NORTE - Jornada 3</pre>\n"
// . "<pre>Ajax (Luizao) 2-0 PSV (Sullendonkey)</pre>";

// $text = "\xF0\x9F\x92\x80 Usuario expulsado\n"
// . "<b>Fermin</b> expulsado de la liga";

// $text = "\xF0\x9F\x91\x8B Nuevo usuario registrado\n"
// . "<b>Antxon</b> se ha unido a la lista de reservas de la liga";

// Telegram::sendMessage([
//     'chat_id' => '-1001241759649',
//     'parse_mode' => 'HTML',
//     'text' => '<b>bold</b>, <strong>bold</strong>
// <i>italic</i>, <em>italic</em>
// <a href="http://www.example.com/">inline URL</a>
// <a href="@Carlitros116">inline mention of a user</a>
// <code>inline fixed-width code</code>
// <pre>pre-formatted fixed-width code block</pre>',
//     'disable_web_page_preview' => true
// ]);


// $messageId = $response->getMessageId();

        // dd($username);

        // $text = "Mensaje de prueba con channel almacenado";
        // send_telegram_notification($text);

        $posts = Post::where('type', '=', 'press')->orderBy('created_at', 'desc')->paginate();
        $last_users = User::where('verified', '=', 1)->orderBy('id', 'desc')->take(8)->get();

        return view('home', compact('posts', 'last_users'));
    }


    public function privacity()
    {
        return view('privacity');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSent(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mensaje'=>'required|string|min:20',
        ]);

        if (request()->honey_pot == null) {
            $forminput = [
                'nombre' => $request->input('nombre'),
                'email' => $request->input('email'),
                'mensaje' => $request->input('mensaje')
            ];
            Mail::to('lpx.torneos@gmail.com')->send(new Contact($forminput));
            $text = 'Nuevo mensaje recibido desde el formulario de contacto.';
            $this->telegram_notification_admin($text);

            return redirect('contacto')->with('success', '¡Mensaje enviado! Gracias por contactarnos.');
        } else {
            return redirect('/');
        }
    }

    public function participants()
    {
        return view('participants');
    }

    public function competitions()
    {
        return view('competitions.index');
    }

    public function competition()
    {
        return redirect(route('competition.league.standing'));
    }

    public function competition_standing()
    {
        return view('competitions.league.standing');
    }

    public function competition_schedule()
    {
        return view('competitions.league.schedule');
    }

    public function competition_statistics()
    {
        return view('competitions.league.statistics');
    }

    public function competition_match()
    {
        return view('competitions.match');
    }

    public function rules()
    {
        return view('rules.index');
    }
}
