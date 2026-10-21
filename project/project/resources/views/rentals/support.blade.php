@extends('rentals.layout')
@include('rentals.control-style')
@section('title', 'Support · '.$panel->name)
@section('brand', $panel->name)
@section('nav')
<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias') : ($owner ? route('user.panels.show', $panel->id) : route('user.dashboard')) }}">Back to dashboard</a>
@endsection
@section('content')
@php
    $inbox = $owner ? route('user.panels.support', $panel->id) : route('rental.support');
    $send = $owner ? route('user.panels.support.store', $panel->id) : route('rental.support.store');
@endphp
<h1>{{ $owner ? 'Customer support' : 'Support' }}</h1>
<div class="grid">
    <section class="card">
        <h2>Conversations</h2>
        @forelse($tickets as $ticket)
            <p><a href="{{ $inbox }}?ticket={{ $ticket->id }}">#{{ $ticket->id }} · {{ $ticket->subject }}</a>
                <small>{{ ucfirst($ticket->status) }} · {{ $ticket->updated_at }}</small></p>
        @empty
            <p class="muted">No conversations yet.</p>
        @endforelse
        {{ $tickets->links() }}
        @unless($owner)
            <h2>New conversation</h2>
            <form method="post" action="{{ $send }}">@csrf
                <label>Subject<input name="subject" maxlength="160" value="{{ old('subject') }}" required></label>
                <label>Message<textarea name="body" rows="5" maxlength="10000" required>{{ old('body') }}</textarea></label>
                <button>Send message</button>
            </form>
        @endunless
    </section>
    <section class="card">
        @if($selected)
            <h2>{{ $selected->subject }}</h2>
            <span class="tag">{{ ucfirst($selected->status) }}</span>
            @foreach($messages as $message)
                <article class="notice">
                    <strong>{{ $message->author_role === 'owner' ? 'Panel support' : 'Customer' }}</strong>
                    <p style="white-space:pre-wrap">{{ $message->body }}</p>
                    <small>{{ $message->created_at }}</small>
                </article>
            @endforeach
            <form method="post" action="{{ $send }}">@csrf
                <input type="hidden" name="ticket" value="{{ $selected->id }}">
                <label>Reply<textarea name="body" rows="5" maxlength="10000" required></textarea></label>
                <label>Status<select name="status"><option value="open">Keep open / reopen</option><option value="closed">Close after reply</option></select></label>
                <button>Send reply</button>
            </form>
        @else
            <h2>Your conversations</h2><p class="muted">Select a conversation to read and reply.</p>
        @endif
    </section>
</div>
@endsection
