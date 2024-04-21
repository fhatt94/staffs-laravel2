<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use App\Models\Chirp;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eager load the user relationship and order by the most recent
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('chirps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $request->user()->chirps()->create($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp): View
    {
        return view('chirps.show', ['chirp' => $chirp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        Gate::authorize('update', $chirp);

        return view('chirps.edit', ['chirp' => $chirp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);  // Assuming there's a 'delete' ability defined

        $chirp->delete();

        return redirect(route('chirps.index'));
    }


   /**
    * Add the Chirp to Favourites.
    */
    public function addToFavourites(Chirp $chirp): RedirectResponse
    {
    // Retrieve 'favourites' from session or create a new collection if it doesn't exist
    $favourites = session('favourites', collect([]));

    // Add the new chirp to the favourites collection
    $favourites->push($chirp);

    // Update the session with the new favourites list
    session(['favourites' => $favourites]);

    // Redirect to the chirps index page
    return redirect(route('chirps.index'));
    }

    /*** Show the Chirps in Favourites
    */
    public function favourites(): View
    {
        $favourites = session('favourites', collect([]));
        return view('chirps.favourites', [

          'chirps' => $favourites,

        ]);
    }

}
