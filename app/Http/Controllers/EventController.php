<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // creation d'un événement
    public function store(Request $request)
    {
        $request->validate([
            // Validation des champs
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'date' => 'required|date',
            'category' => 'required|string',
            'max_participants' => 'required|integer',
        ]);

        // Création des info d un match
        $event = Event::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'location' => $request->location,
            'date' => $request->date,
            'category' => $request->category,
            'max_participants' => $request->max_participants,
        ]);

        return response()->json($event, 201); // Retourne l'événement créé avec le code 201
    }

    // Méthode pour récupérer tous les événements http://127.0.0.1:8000/api/events/
    //on met un ? au debut et puis des &
    // Afficher tous les événements avec des filtres
    public function index(Request $request)
    {
        $query = Event::query();

        // Filtrer par catégorie /?category=sport
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filtrer par lieu  /?location=ville
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filtrer par date /?date=2022-03-10
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        // Exécuter la requête et retourner les résultats
        //on retourne 5 resultat par page /?page=1
        $events = $query->paginate(5);

        return response()->json($events);
    }

    // Methode pour récupérer un événement par son ID
    public function show($slug, $id)
    {
        $event = Event::where('id', $id)->where('slug', $slug)->firstOrFail();
        return response()->json($event);
    }

    // Maitode pour mettre à jour un événement avec methode put
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id); //afficher ou retourner une erreur 404

        $request->validate([  // Validation des champs
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'date' => 'required|date',
            'category' => 'required|string',
            'max_participants' => 'required|integer',
        ]);

        $event->update([ // Mise à jour des informations de l'événement avec methode put
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'location' => $request->location,
            'date' => $request->date,
            'category' => $request->category,
            'max_participants' => $request->max_participants,
        ]);

        return response()->json($event);
    }

    // supprimer un evénement
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
