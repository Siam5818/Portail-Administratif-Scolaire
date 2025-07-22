<?php

namespace App\services;

use App\Models\Note;

class NoteService{
    public function index()
    {
        return Note::all();
    }

    public function store(array $request)
    {
        return Note::create($request);
    }

    public function show($id)
    {
        return Note::find($id);
    }

    public function update(array $request, $id)
    {
        $Note = Note::findOrFail($id);
        $Note->update($request);
        return $Note;
    }

    public function destroy($id)
    {
        Note::destroy($id);
        return true;
    }
}