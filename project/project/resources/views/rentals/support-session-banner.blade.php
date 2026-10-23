<aside role="status" style="position:relative;z-index:1000;padding:12px 20px;background:#6c3511;color:#fff;font:14px/1.5 system-ui;display:flex;gap:12px;align-items:center;flex-wrap:wrap">
<strong>Read-only support session: {{ $supportUser->username }}</strong><span>Expires {{ $supportExpiry }}</span>
<form method="post" action="{{ url('/support-session/stop') }}" style="margin:0">@csrf<button style="margin:0;padding:7px 14px;border:1px solid #fff;border-radius:8px;background:transparent;color:#fff;cursor:pointer">Return to admin</button></form>
</aside>
