<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Porteur;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PorteurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entreprises = DB::table('organisations')->select('RaisonSociale', 'Id')->where('IdTypeOrga', 1)->get();
        $associations = DB::table('organisations')->select('RaisonSociale', 'Id')->where('IdTypeOrga', 2)->get();
        return view('Maquette InscriptionPorteurProjet.SusbcribePortProjet', [
            'entreprises' => $entreprises,
            'associations' => $associations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Ajout des règles de validations
        $this->validate($request, [
            'password' => 'required|min:12|regex:#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#',
            'password2' => 'required|min:12|regex:#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#',
            'mail' => 'required|email',
            'nom' => 'required',
            'prenom' => 'required',
            'pseudo' => 'required',
            'tel' => 'required',
            'Poste' => 'required',
            'mentionsLegales' => 'required'
        ]);
        $password = $request->get('Mot de Passe');
        $confPassword = $request->get('Mot de Passe 2');
        if (((DB::table('porteurs')->where('Email', $request->get("mail"))->count()) == 0)) {
            if ((DB::table('porteurs')->where('Login', $request->get('pseudo'))->count()) == 0) {
                if ($password == $confPassword) {
                    //Vérification si le client a choisi une entreprise, une association ou s'il est un particulier
                    if ($request->get('NomEntreprise') != "") {
                        $Orga = (int) $request->get('NomEntreprise'); //
                    } else if ($request->get('NomAssociation') != "") {
                        $Orga = (int) $request->get('NomAssociation');
                    } else if ($request->get('typeOrganisation') == "particulier") {
                        $Orga = null; //Si c'est un particulier alors il n'appartient à aucune Organisation
                    } else {
                        return redirect()->route('PortProjetSub')->withErrors('Vous devez entrez une Entreprise ou une Association ou un poste en tant que Particulier');
                    }
                    $porteur = new Porteur([
                        'IdOrga' => $Orga,
                        'Nom' => $request->get('nom'),
                        'Prenom' => $request->get('prenom'),
                        'Email' => $request->get('mail'),
                        'Login' => $request->get('pseudo'),
                        'Mdp' => Hash::make($password),
                        'Telephone' => $request->get('tel'),
                        'Poste' => $request->get('Poste')
                    ]);
                    $porteur->save();
                    $id = DB::table('porteurs')->where('Login', $request->get('pseudo'))->value('Id');
                    DB::table('organisations')->where('Id', (int) $Orga)->update(['IdPorteur' => $id]);
                    return redirect()->route('connect')->with('sucess', 'Porteur ajouté');
                } else {
                    return redirect()->route('PortProjetSub')->withErrors('Les Mots de Passes ne correspondent pas', 'Diffmdp');
                }
            } else {
                return redirect()->route('PortProjetSub')->withErrors('Ce pseudo est déjà utilisé par l\'un de nos clients', 'MailUsed');
            }
        } else {
            return redirect()->route('PortProjetSub')->withErrors('Cette Adresse E-mail est déjà utilisé par l\'un de nos clients');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
