<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // Méthode pour créer un événement
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'date' => 'required|date',
            'category' => 'required|string',
            'max_participants' => 'required|integer',
        ]);

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

    // Méthode pour récupérer tous les événements
    // Afficher tous les événements avec des filtres
    public function index(Request $request)
    {
        $query = Event::query();

        // Filtrer par catégorie si le paramètre "category" est passé
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filtrer par lieu si le paramètre "location" est passé
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filtrer par date si le paramètre "date" est passé
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        // Exécuter la requête et retourner les résultats
        $events = $query->paginate(5);

        return response()->json($events);
    }

    // Méthode pour récupérer un événement par son ID
    public function show($slug, $id)
    {
        $event = Event::where('id', $id)->where('slug', $slug)->firstOrFail();
        return response()->json($event);
    }

    // Méthode pour mettre à jour un événement
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'date' => 'required|date',
            'category' => 'required|string',
            'max_participants' => 'required|integer',
        ]);

        $event->update([
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

    // Méthode pour supprimer un événement
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
