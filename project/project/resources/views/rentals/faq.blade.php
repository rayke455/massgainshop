<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>FAQ · {{ $panel->name }}</title>@include('rentals.workspace-style')</head>
<body><div class="workspace"><header class="workspace-header"><a href="/">{{ $panel->name }}</a><a href="/">Back to panel</a></header><main><h1>Frequently asked questions</h1>
@forelse($entries as $entry)<details class="content-card"><summary>{{ $entry->title }}</summary><p style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $entry->body }}</p></details>@empty<p>No answers published yet. Contact support for help.</p>@endforelse
@include('rentals.content-links')</main></div></body></html>
