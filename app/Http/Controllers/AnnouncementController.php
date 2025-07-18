<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
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
            'title' => ['required', 'max:255', 'string'],
            'content' => ['required', 'string']
        ]);

        $request->user()->announcements()->create($validated);

        return to_route('announcement.index')->with('status', [
            'type' => 'success',
            'message' => 'Announcement published',
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
            'title' => ['required', 'max:255', 'string'],
            'content' => ['required', 'string']
        ]);

        $announcement->update($validated);

        return to_route('announcement.index')->with('status', [
            'type' => 'success',
            'message' => 'Announcement updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return to_route('announcement.index')->with('status', [
            'type' => 'success',
            'message' => 'Announcement deleted',
        ]);
    }
}
