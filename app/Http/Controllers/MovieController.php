<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use Inertia\Inertia;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $model = Movie::query()
            ->where('name', 'like', '%'.request()->query('search').'%')
            ->orWhere('description', 'like', '%'.request()->query('search').'%')
            ->orderBy(
                request('sort_field', 'created_at'),
                request('sort_direction', 'desc')
            )
            ->paginate(5)
            ->appends(request()->query());

        return Inertia::render('Movies/Index', [
            'model' => MovieResource::collection($model),
            'queryParams' => request()->query(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Movies/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovieRequest $request)
    {
       Movie::create($request->validated());

        session()->flash('message', 'Successfully created a new movie!');

        return redirect(route('movies.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return Inertia::render('Movies/Show', [
            'movie' => new MovieResource($movie),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        return Inertia::render('Movies/Update', [
            'movie' => new MovieResource($movie),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $movie->update($request->validated());

        session()->flash('message', 'Successfully updated movie information!');

        return redirect(route('movies.index', $request->query()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        session()->flash('message', 'Successfully deleted movie information!');

        return redirect(route('movies.index'));
    }
}
