<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArtworkController extends Controller
{
    /**
     * Get the artwork storage path — Windows/Linux compatible
     */
    private function getStoragePath(): string
    {
        $path = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'artwork');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        return $path;
    }

    /**
     * Upload artwork to an order (user side)
     */
    public function upload(Request $request, int $orderId)
    {
        $user = Auth::user();
        $order = DB::selectOne("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$orderId, $user->id]);
        if (!$order) abort(404);

        $request->validate([
            'artwork_files'   => 'required|array|min:1',
            'artwork_files.*' => 'file|max:102400|mimes:pdf,jpg,jpeg,png,ai,eps,tiff,tif',
            'order_item_id'   => 'nullable|integer',
            'label'           => 'nullable|string|max:100',
        ]);

        if (!$request->hasFile('artwork_files')) {
            return back()->with('error', 'No files received. Make sure form has enctype="multipart/form-data".');
        }

        $uploaded    = 0;
        $errors      = [];
        $storagePath = $this->getStoragePath();

        foreach ($request->file('artwork_files') as $i => $file) {
            if (!$file || !$file->isValid()) {
                $errors[] = 'File ' . ($i + 1) . ' is invalid (error code: ' . ($file ? $file->getError() : 'null') . ')';
                continue;
            }

            try {
                $originalName = $file->getClientOriginalName();
                $ext          = strtolower($file->getClientOriginalExtension());
                $filename     = time() . '_' . uniqid() . '.' . $ext;

                $file->move($storagePath, $filename);

                $fullSavedPath = $storagePath . DIRECTORY_SEPARATOR . $filename;

                if (!file_exists($fullSavedPath)) {
                    Log::error('Artwork move failed', ['path' => $fullSavedPath]);
                    $errors[] = $originalName . ' could not be saved.';
                    continue;
                }

                $filePath = 'storage/artwork/' . $filename;

                DB::table('order_artwork')->insert([
                    'order_id'      => $orderId,
                    'order_item_id' => $request->order_item_id ?: null,
                    'file_name'     => $originalName,
                    'file_path'     => $filePath,
                    'file_type'     => $ext,
                    'file_size'     => filesize($fullSavedPath),
                    'label'         => $request->label ?: ('File ' . ($i + 1)),
                    'uploaded_by'   => 'customer',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                if ($uploaded === 0 && $request->order_item_id) {
                    DB::table('order_items')
                        ->where('id', $request->order_item_id)
                        ->whereNull('artwork_url')
                        ->update(['artwork_url' => $filePath]);
                }

                $uploaded++;

            } catch (\Exception $e) {
                Log::error('Artwork upload error', [
                    'order_id'   => $orderId,
                    'file_index' => $i,
                    'message'    => $e->getMessage(),
                ]);
                $errors[] = 'File ' . ($i + 1) . ' failed: ' . $e->getMessage();
            }
        }

        if ($uploaded > 0 && empty($errors)) {
            return back()->with('success', $uploaded . ' artwork file(s) uploaded successfully!');
        } elseif ($uploaded > 0) {
            return back()->with('success', $uploaded . ' file(s) uploaded.')
                         ->with('warning', 'Some files failed: ' . implode(', ', $errors));
        }

        return back()->with('error', !empty($errors)
            ? implode(' | ', $errors)
            : 'Upload failed. Check file type (pdf,jpg,png,ai,eps,tiff) and size (max 100MB).'
        );
    }

    /**
     * Upload artwork from admin side
     */
    public function adminUpload(Request $request, int $orderId)
    {
        $order = DB::selectOne("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) abort(404);

        $request->validate([
            'artwork_files'   => 'required|array|min:1',
            'artwork_files.*' => 'file|max:102400|mimes:pdf,jpg,jpeg,png,ai,eps,tiff,tif',
            'order_item_id'   => 'nullable|integer',
            'label'           => 'nullable|string|max:100',
        ]);

        if (!$request->hasFile('artwork_files')) {
            return back()->with('error', 'No files received.');
        }

        $uploaded    = 0;
        $storagePath = $this->getStoragePath();

        foreach ($request->file('artwork_files') as $i => $file) {
            if (!$file || !$file->isValid()) continue;

            try {
                $originalName = $file->getClientOriginalName();
                $ext          = strtolower($file->getClientOriginalExtension());
                $filename     = time() . '_' . uniqid() . '.' . $ext;

                $file->move($storagePath, $filename);

                $fullSavedPath = $storagePath . DIRECTORY_SEPARATOR . $filename;
                if (!file_exists($fullSavedPath)) continue;

                DB::table('order_artwork')->insert([
                    'order_id'      => $orderId,
                    'order_item_id' => $request->order_item_id ?: null,
                    'file_name'     => $originalName,
                    'file_path'     => 'storage/artwork/' . $filename,
                    'file_type'     => $ext,
                    'file_size'     => filesize($fullSavedPath),
                    'label'         => $request->label ?: ('File ' . ($i + 1)),
                    'uploaded_by'   => 'admin',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                $uploaded++;
            } catch (\Exception $e) {
                Log::error('Admin artwork upload error: ' . $e->getMessage());
            }
        }

        if ($uploaded > 0) {
            return back()->with('success', $uploaded . ' file(s) uploaded.');
        }
        return back()->with('error', 'Upload failed. Check file type and size.');
    }

    /**
     * Delete artwork file
     */
    public function delete(Request $request, int $id)
    {
        $art = DB::table('order_artwork')->where('id', $id)->first();
        if (!$art) abort(404);

        if (!Auth::guard('admin')->check()) {
            $order = DB::selectOne("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$art->order_id, Auth::id()]);
            if (!$order) abort(403);
        }

        $fullPath = public_path($art->file_path);
        if (file_exists($fullPath)) @unlink($fullPath);

        DB::table('order_artwork')->where('id', $id)->delete();
        return back()->with('success', 'Artwork file deleted.');
    }

    /**
     * Download artwork file
     */
    public function download(int $id)
    {
        $art = DB::table('order_artwork')->where('id', $id)->first();
        if (!$art) abort(404);

        if (!Auth::guard('admin')->check()) {
            $order = DB::selectOne("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$art->order_id, Auth::id()]);
            if (!$order) abort(403);
        }

        $fullPath = public_path($art->file_path);
        if (!file_exists($fullPath)) abort(404, 'File not found.');

        return response()->download($fullPath, $art->file_name);
    }
}
