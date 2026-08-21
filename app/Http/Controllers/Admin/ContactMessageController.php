<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);
        $messages = ContactMessage::query()
            ->when($request->filled('q'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('q').'%')
                ->orWhere('email', 'like', '%'.$request->string('q').'%')
                ->orWhere('subject', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.messages.index', compact('messages'));
    }

    public function show(Request $request, ContactMessage $message)
    {
        $this->authorizeAdmin($request);
        if (! $message->read_at) {
            $message->update(['read_at' => now(), 'status' => $message->status === 'new' ? 'read' : $message->status]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function update(Request $request, ContactMessage $message)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate(['status' => ['required', Rule::in(['new', 'read', 'replied', 'closed'])]]);
        $message->update([...$data, 'read_at' => $message->read_at ?? now()]);

        return back()->with('success', 'Status pesan diperbarui.');
    }

    public function destroy(Request $request, ContactMessage $message)
    {
        $this->authorizeAdmin($request);
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Pesan dihapus.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless((bool) $request->user()?->is_admin, 403);
    }
}
