<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $title }} · {{ $panel->name }}</title>
@include('rentals.workspace-style')
</head><body><div class="workspace"><header class="workspace-header"><a href="{{ \App\Services\PanelRentals::currentId() === $panel->id ? '/' : route('panels.show', $panel->slug) }}">{{ $panel->name }} · Back to panel</a></header>
<main><h1>{{ $title }}</h1><article class="content-card" style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $body }}</article></main>
</div></body></html>
