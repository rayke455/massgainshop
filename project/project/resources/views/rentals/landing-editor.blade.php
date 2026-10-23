@extends('rentals.layout')
@include('rentals.owner-subpage')
@section('title', 'Landing page designer')
@section('nav')<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias') : route('user.panels.show', $panel->id) }}">Back to control panel</a>@endsection
@section('content')
<h1>Landing page designer</h1>
<p class="muted">Make {{ $panel->name }} your own. Edit HTML and CSS, preview your design, then publish when ready.</p>
<div class="notice">The site header, Sign in, Sign up and guest ordering stay outside your template. Scripts, forms, embeds and custom navigation are blocked. Use HTTPS image URLs. Your design cannot change checkout or account pages.</div>
<p><span class="tag">{{ isset($panel->workspace()['landing_published']) ? 'Custom design published' : 'Default design live' }}</span></p>
<form method="post" action="{{ ($ownerPreview ?? false) ? '#' : route('user.panels.landing.store', $panel->id) }}">@csrf
<div class="grid">
<div><label for="landing-html">HTML body</label><textarea id="landing-html" name="html" maxlength="100000" spellcheck="false">{{ old('html', $draft['html']) }}</textarea></div>
<div><label for="landing-css">CSS styles</label><textarea id="landing-css" name="css" maxlength="50000" spellcheck="false">{{ old('css', $draft['css']) }}</textarea></div>
</div>
<div class="inline"><button name="action" value="save">Save draft</button><button name="action" value="preview" formtarget="landing-design-preview">Preview changes</button><button name="action" value="publish">Publish design</button><button name="action" value="restore" formnovalidate>Restore default</button></div>
</form>
<h2>Design preview</h2><p class="muted">Preview changes does not save or publish. Resize your browser to check the mobile layout.</p>
<iframe name="landing-design-preview" title="Landing design preview" sandbox="" referrerpolicy="no-referrer" srcdoc="{{ \App\Services\RentalLanding::document($draft) }}" style="width:100%;height:700px;border:1px solid #dbe1ed;border-radius:16px;background:white"></iframe>
<style>textarea{width:100%;min-height:380px;padding:16px;border:1px solid #dbe1ed;border-radius:12px;background:#10182a;color:#dfebff;font:14px/1.7 monospace;resize:vertical}.inline button{font-size:14px}</style>
@endsection
