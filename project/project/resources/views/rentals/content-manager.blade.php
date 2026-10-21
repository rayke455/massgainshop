@extends('rentals.layout')
@include('rentals.control-style')
@section('title', 'Pages & announcements · '.$panel->name)
@section('brand', $panel->name)
@section('nav')<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias') : route('user.panels.show', $panel->id) }}">Back to control panel</a>@endsection
@section('content')
<h1>Pages & announcements</h1>
<p class="muted">Publish pages, navigation links, FAQ answers and in-app customer announcements. Drafts stay private. Content is plain text; scripts and HTML are not executed.</p>
<div class="grid">
<section class="card"><h2>Your content</h2><a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen' => 'content']) : route('user.panels.content', $panel->id) }}">+ New entry</a>
@forelse($entries as $entry)
<article class="notice"><a href="{{ route('user.panels.content', ['panelId' => $panel->id, 'edit' => $entry->id]) }}">{{ $entry->title }}</a><small>{{ ucfirst($entry->kind) }} · {{ $entry->published ? 'Published' : 'Draft' }} · Position {{ $entry->position }}</small></article>
@empty<p>No content yet.</p>@endforelse
{{ $entries->links() }}</section>
<section class="card"><h2>{{ $selected ? 'Edit entry' : 'New entry' }}</h2>
<form method="post" action="{{ route('user.panels.content.store', $panel->id) }}">@csrf
@if($selected)<input type="hidden" name="id" value="{{ $selected->id }}">@endif
<label>Type<select name="kind">@foreach(['page'=>'Page','link'=>'External link','faq'=>'FAQ answer','notice'=>'Customer announcement'] as $value=>$label)<option value="{{ $value }}" @selected(old('kind', $selected->kind ?? 'page') === $value)>{{ $label }}</option>@endforeach</select></label>
<label>Title<input name="title" required maxlength="160" value="{{ old('title', $selected->title ?? '') }}"></label>
<label>URL name<input name="slug" required pattern="[a-z0-9]+(-[a-z0-9]+)*" maxlength="100" value="{{ old('slug', $selected->slug ?? '') }}" placeholder="about-us"></label>
<small>Lowercase letters, numbers and hyphens. Pages use /pages/your-url-name.</small>
<label>Content<textarea name="body" rows="10" maxlength="20000">{{ old('body', $selected->body ?? '') }}</textarea></label>
<label>External URL (links only)<input name="url" type="url" maxlength="2048" placeholder="https://example.com" value="{{ old('url', $selected->url ?? '') }}"></label>
<label>Position<input name="position" type="number" min="0" max="10000" value="{{ old('position', $selected->position ?? 0) }}" required></label>
<label>Status<select name="published"><option value="0" @selected(!old('published', $selected->published ?? false))>Draft / hidden</option><option value="1" @selected(old('published', $selected->published ?? false))>Published</option></select></label>
<button>Save entry</button>
</form></section></div>
@endsection
