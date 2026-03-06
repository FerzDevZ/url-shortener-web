<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceSessionController extends Controller
{
    /**
     * Switch the active workspace in session.
     */
    public function switch(Request $request)
    {
        $validated = $request->validate([
            'workspace_id' => 'nullable|exists:workspaces,id',
        ]);

        $workspaceId = $validated['workspace_id'];

        if ($workspaceId) {
            // Verifikasi user adalah anggota workspace tersebut
            $isMember = auth()->user()->workspaces()->where('workspace_id', $workspaceId)->exists();
            if (!$isMember) {
                abort(403, 'Anda bukan anggota workspace ini.');
            }
            
            session(['active_workspace_id' => $workspaceId]);
            $workspace = Workspace::find($workspaceId);
            return back()->with('success', "Berpindah ke Workspace: {$workspace->name}");
        }

        session()->forget('active_workspace_id');
        return back()->with('success', 'Berpindah ke Workspace Personal');
    }
}
