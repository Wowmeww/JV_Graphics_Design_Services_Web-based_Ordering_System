<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['string', 'nullable']
        ]);
        $announcements = Announcement::with(['user'])
            ->where(function ($q) use ($request) {
                if ($request->input('search')) {
                    $search = '%' . $request->input('search') . '%';
                    $q->where('title', 'like', $search)
                        ->orWhere('content', 'like', $search);
                }
            })
            ->latest()->get();
        return Inertia::render('auth/announcement/Announcements', [
            'announcements' => $announcements,
            'search' => $request->input('search')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('auth/announcement/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image'   => ['nullable', 'image', 'max:2048'],
        ]);
        $announcement = $request->user()->announcements()->create([
            'title'   => $validated['title'],
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/announcement', 'public');
            $announcement->update([
                'image_url' => $path,
            ]);
        }

        // ✅ Redirect with success message
        return to_route('announcement.index')->with('status', [
            'type'    => 'success',
            'message' => 'Announcement published successfully.',
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return Inertia::render('auth/announcement/Edit', [
            'announcement' => $announcement
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'content'       => ['required', 'string'],
            'image'         => ['nullable', 'image', 'max:2048', 'sometimes'], // new image upload
            'delete_image'  => ['nullable', 'boolean'], // default false if missing
        ]);

        // ✅ Update base fields
        $announcement->update([
            'title'   => $validated['title'],
            'content' => $validated['content'],
        ]);

        // ✅ If user wants to delete the image
        if (!empty($validated['delete_image']) && $announcement->image_url) {
            if (Storage::disk('public')->exists($announcement->image_url)) {
                Storage::disk('public')->delete($announcement->image_url);
            }

            $announcement->update(['image_url' => null]);
        }

        // ✅ If a new image is uploaded
        if ($request->hasFile('image')) {
            // Remove old file before replacing
            if ($announcement->image_url && Storage::disk('public')->exists($announcement->image_url)) {
                Storage::disk('public')->delete($announcement->image_url);
            }

            $path = $request->file('image')->store('images/announcement', 'public');
            $announcement->update(['image_url' => $path]);
        }

        return to_route('announcement.index')->with('status', [
            'type'    => 'success',
            'message' => 'Announcement updated successfully.',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->image_url && Storage::disk('public')->exists($announcement->image_url)) {
            Storage::disk('public')->delete($announcement->image_url);
        }

        $announcement->delete();

        return to_route('announcement.index')->with('status', [
            'type' => 'success',
            'message' => 'Announcement deleted successfully.',
        ]);
    }
}
