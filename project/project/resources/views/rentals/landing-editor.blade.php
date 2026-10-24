@extends('rentals.layout')
@include('rentals.owner-subpage')
@section('title', 'Landing page designer')
@section('nav')<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias') : route('user.panels.show', $panel->id) }}">Back to control panel</a>@endsection
@section('content')
@php($designSettings = (($ownerPreview ?? false) ? route('rental.preview.alias') : route('user.panels.show', $panel->id)).'#common-branding')
<div class="owner-settings">
<h1>Design &amp; Content</h1>
<h3>Logo &amp; Favicon</h3>
@foreach(['logo' => 'Logo', 'dark_logo' => 'Dark theme logo', 'favicon' => 'Favicon'] as $kind => $label)
<form class="settings-row appearance-row" method="post" enctype="multipart/form-data" action="{{ ($ownerPreview ?? false) ? '#' : route('user.panels.appearance', $panel->id) }}">
@csrf<input type="hidden" name="kind" value="{{ $kind }}">
<span>{{ $label }}<small>{{ $kind === 'favicon' ? 'Square PNG recommended' : '520 × 128 recommended' }} · PNG, JPG, WebP · 2 MB max</small></span>
<span class="appearance-actions">
<span class="appearance-sample {{ $kind }}">@if($designLogo = \App\Services\PanelRentals::logoUrl($panel, $kind))<img src="{{ $designLogo }}" alt="{{ $label }} preview">@else<span>{{ $kind === 'favicon' ? mb_substr($panel->name, 0, 1) : $panel->name }}</span>@endif</span>
<label class="appearance-upload">Upload<input type="file" name="image" accept="image/png,image/jpeg,image/webp" aria-label="Upload {{ $label }}" required onchange="if(this.files.length) this.form.requestSubmit()"></label>
<noscript><button type="submit">Save image</button></noscript>
</span>
</form>
@endforeach
<h3>Colors</h3>
<form class="settings-row appearance-row" method="post" action="{{ ($ownerPreview ?? false) ? '#' : route('user.panels.appearance', $panel->id) }}">@csrf<input type="hidden" name="kind" value="color">
<label for="appearance-color">Brand color<small>Buttons and highlights on your storefront</small></label><span class="appearance-actions"><input id="appearance-color" type="color" name="color" value="{{ $panel->color ?: '#2563eb' }}"><button type="submit">Save</button></span></form>
<a class="settings-row" href="{{ $designSettings }}"><span>Panel name</span><span>{{ $panel->name }} ›</span></a>
<h3>Landing page</h3>
</div>
<details class="landing-code-editor"><summary>Landing page designer · HTML &amp; CSS</summary>
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
</details>
<style>
.appearance-row{min-height:101px!important;gap:24px;margin:0}.appearance-row>span:first-of-type,.appearance-row>label{flex:1;margin:0;font-weight:400}.appearance-row small{font-size:13px;color:#7f8a95;margin-top:6px}.appearance-actions{display:flex;align-items:center;gap:24px;flex-shrink:0}.appearance-sample{width:140px;height:44px;background:#edf0f4;border:1px solid #34414d;border-radius:6px;display:flex;align-items:center;justify-content:center;overflow:hidden;color:#24364a;font-size:15px;font-weight:600;padding:6px}.appearance-sample.dark_logo{background:#10171e;color:#e0e6ec}.appearance-sample img{max-height:100%;max-width:100%;object-fit:contain}.appearance-sample.favicon img{width:24px;height:24px}.appearance-upload,.appearance-actions button{position:relative;overflow:hidden;display:inline-block;border:0;border-radius:24px!important;background:#2386ee!important;color:#fff!important;font-size:15px;font-weight:400;margin:0!important;padding:9px 22px!important;cursor:pointer}.appearance-upload input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;padding:0}.appearance-upload:focus-within{outline:2px solid white;outline-offset:3px}.appearance-actions input[type=color]{width:55px;height:38px;padding:3px;background:#25313b;border:1px solid #40505d;border-radius:6px}fieldset:disabled .appearance-upload{cursor:not-allowed;opacity:.65}@media(max-width:600px){.appearance-row{flex-wrap:wrap;gap:14px;padding:20px 0!important}.appearance-row>span:first-of-type{flex-basis:100%}.appearance-actions{gap:14px}.appearance-sample{width:130px}}
</style>
<style>.landing-code-editor{border-bottom:1px solid #29323a;padding:18px 0}.landing-code-editor>summary{cursor:pointer;color:#d5dce2;font-size:16px}.landing-code-editor[open]>summary{margin-bottom:24px}textarea{width:100%;min-height:380px;padding:16px;border:1px solid #dbe1ed;border-radius:12px;background:#10182a;color:#dfebff;font:14px/1.7 monospace;resize:vertical}.inline button{font-size:14px}</style>
@endsection
