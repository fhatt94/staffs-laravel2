<?php

namespace App\Http\Controllers;

use App\Models\chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;


class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //public function index()
/*   public function index(): Response
    {
        return response('Hello, World! This is Chirps');
    }
*/
    public function index(): View
    {
        //return view('chirps.index');
        return view('chirps.index', [
            'chirps' => chirp::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):RedirectResponse
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
    public function show(chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(chirp $chirp):View
    {
        Gate::authorize('update', $chirp);
 
        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
         //
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
    public function destroy(chirp $chirp):RedirectResponse
    {
        Gate::authorize('delete', $chirp);
 
        $chirp->delete();
 
        return redirect(route('chirps.index'));

    }

    //Add the Chirp to Favourites
    public function addToFavourites(Chirp $chirp): RedirectResponse
    {
        $favourites = session('favourites', collect([]));
        $favourites->push($chirp);
        session(['favourites' => $favourites]);
        return redirect(route('chirps.index'));
   }

      //Show the Chirps in Favourites
    public function favourites(): View   {
        $favourites = session('favourites', collect([]));
        return view('chirps.favourites', [
              'chirps' => $favourites,
        ]);
   }


}
