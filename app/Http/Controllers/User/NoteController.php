<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Engineer;
use App\Models\WorkOrderTimeLog;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function createWorkOrderTimeLog($tableName, $columnName, $wo_id, $date, $preLog, $value, $toUser = null, $type,  $msg, $id)
    {
        $wo_log = new WorkOrderTimeLog();
        $wo_log->wo_id = $wo_id;
        $wo_log->pre_log_id = $preLog->id ?? null;
        $wo_log->identity = $id ?? null;
        $wo_log->by_user = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $wo_log->to_user = $toUser ?? null;
        $wo_log->event_title = $msg;
        $wo_log->table_name = $tableName;
        $wo_log->column_name = $columnName;
        $wo_log->value_type = $type;
        $wo_log->value = $value;
        $wo_log->recorded_at = $date;
        $wo_log->save();

    }

    public function store(Request $request, $id)
    {
        $note = new Note();
        $note->wo_id = $id;
        $note->auth_id = Auth::user()->id;
        $note->note_type = $request->note_type ?? 'general_notes';
        $note->note = $request->note;

        $note->save();

        $noteType = 
        $note->note_type == 'general_notes' ? 'General Note' :
        ($note->note_type == 'dispatch_notes' ? 'Dispatch Note' :
        ($note->note_type == 'billing_notes' ? 'Billing Note' :
        ($note->note_type == 'tech_support_notes' ? 'Tech Support Note' :
        ($note->note_type == 'close_out_notes' ? 'Close Out Note' : ''))));

        $this->createWorkOrderTimeLog('notes', '', $note->wo_id, $note->updated_at, '', $note->note, '', 'nrml_text', $noteType.' Added', $id);
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

        $noteType = 
        $note->note_type == 'general_notes' ? 'General Note' :
        ($note->note_type == 'dispatch_notes' ? 'Dispatch Note' :
        ($note->note_type == 'billing_notes' ? 'Billing Note' :
        ($note->note_type == 'tech_support_notes' ? 'Tech Support Note' :
        ($note->note_type == 'close_out_notes' ? 'Close Out Note' : ''))));

        $this->createWorkOrderTimeLog('notes', '', $note->wo_id, $note->updated_at, '', $note->note, '', 'nrml_text', $noteType.' Added', $id);
    }

    public function storeCloseout(Request $request, $id, $techId = null)
    {

        $note = new Note();

        $eng = Engineer::find($techId);

        $note->wo_id = $id;
        $note->tech_id = $techId;
        $note->auth_id = Auth::user()->id;
        $note->note_type = 'close_out_notes';
        $note->note = $request->note;

        $note->save();

        $this->createWorkOrderTimeLog('notes', '', $note->wo_id, $note->updated_at, '', $note->note, '', 'nrml_text', $techId ? 'Close Out Note Added For '.$eng->name : 'Close Out Note Added', $note->id);
    }
}
