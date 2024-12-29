<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function store(Request $request, $id)
    {
        $note = new Note();
        $note->wo_id = $id;
        $note->auth_id = Auth::user()->id;
        $note->note_type = $request->note_type ?? 'general_notes';
        $note->note = $request->note;

        $note->save();
    }

    public function storeSubNote(Request $request, $id)
    {
        $parentNote = Note::find($id);

        $note = new Note();
        $note->wo_id = $parentNote->wo_id;
        $note->related_note = $id;
        $note->auth_id = Auth::user()->id;
        $note->note_type = $parentNote->note_type ?? 'general_notes';
        $note->note = $request->note;

        $note->save();
    }

    public function storeCloseout(Request $request, $id, $techId = null)
    {

        $note = new Note();
        $note->wo_id = $id;
        $note->tech_id = $techId;
        $note->auth_id = Auth::user()->id;
        $note->note_type = 'close_out_notes';
        $note->note = $request->note;

        $note->save();
    }
}
