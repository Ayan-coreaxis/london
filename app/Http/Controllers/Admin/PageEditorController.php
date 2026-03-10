<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageEditorController extends Controller
{
    // Return all saved edits for a given page path
    public function getEdits(Request $request)
    {
        $path  = $request->query('path', '/');
        $edits = DB::table('page_edits')->where('page_path', $path)->get();
        return response()->json($edits);
    }

    // Save / update edits for a page
    public function saveEdits(Request $request)
    {
        $path  = $request->input('path', '/');
        $edits = $request->input('edits', []);

        foreach ($edits as $edit) {
            if (empty($edit['selector'])) continue;
            DB::table('page_edits')->updateOrInsert(
                ['page_path' => $path, 'selector' => $edit['selector']],
                [
                    'styles'     => json_encode($edit['styles'] ?? []),
                    'content'    => $edit['content'] ?? null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        // Delete removed edits
        $kept = array_column($edits, 'selector');
        if (!empty($kept)) {
            DB::table('page_edits')
                ->where('page_path', $path)
                ->whereNotIn('selector', $kept)
                ->delete();
        }

        return response()->json(['ok' => true]);
    }

    // Reset all edits for a page
    public function resetPage(Request $request)
    {
        $path = $request->input('path', '/');
        DB::table('page_edits')->where('page_path', $path)->delete();
        return response()->json(['ok' => true]);
    }
}
