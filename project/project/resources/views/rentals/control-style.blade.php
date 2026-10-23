@push('styles')
<style>
/* Scoped to the tenant management view; customer storefronts keep their own branding. */
:root{color-scheme:dark}
textarea{width:100%;font:inherit;resize:vertical;background:#111c24;color:#edf1f5;border:1px solid #40515f;border-radius:10px;padding:12px}
body{background:#131b21;color:#edf1f5}
header{border-bottom:1px solid #29343c}
a{color:#a7c6ff}
h1{font-size:26px;margin-bottom:12px}
h2{font-size:21px}
.muted,small{color:#a9b6c2}
.card{background:#1a252d;border-color:#303d47;border-radius:16px}
input:not([type=checkbox]),select{background:#111c24;border-color:#40515f;color:#edf1f5}
input:focus-visible,select:focus-visible,a:focus-visible,button:focus-visible{outline:3px solid #94baff;outline-offset:3px}
button,.button{background:#5174ec;color:#fff}
button:disabled{opacity:.5;cursor:not-allowed}
.tag{background:#2b3851;color:#c5d5ff}
.notice{background:#22364c;color:#e5efff}
.error{background:#482830;color:#ffd9df}
td,th{border-color:#34424d}
.tenant-section{scroll-margin-top:24px;min-width:0}
.control-heading{margin:24px 0}
.control-group{background:#18232b;border:1px solid #293640;border-radius:16px;overflow:hidden;margin-bottom:20px}
.control-group>a{display:grid;grid-template-columns:36px minmax(0,1fr) 16px;gap:16px;align-items:center;padding:20px;text-decoration:none;color:#eef2f7;border-bottom:1px solid #293640;font-weight:600}
.control-group>a:last-child{border-bottom:0}
.control-group>a:hover{background:#23313c}
.control-group small{font-weight:400;font-size:13px;margin-top:3px}
.control-icon{display:grid;place-items:center;width:34px;height:34px;border-radius:10px;font-size:22px;color:#fff}
.purple{background:#7355d8}.green{background:#24896b}.blue{background:#277ec6}.orange{background:#b8592d}
.control-note{font-size:13px}
.control-back{display:inline-block;margin:12px 0;text-decoration:none}
#tenant-control{max-width:760px;margin:0 auto 28px}
.tenant-overview{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin:20px 0}
.tenant-overview>div{background:#18232b;border:1px solid #293640;border-radius:14px;padding:14px;min-width:0}
.tenant-overview strong{display:block;font-size:18px;overflow-wrap:anywhere}.tenant-overview small{font-size:12px}
.tenant-tabs{display:flex;gap:8px;overflow-x:auto;padding:4px 0 18px;scrollbar-width:thin}.tenant-tabs a{flex:0 0 auto;background:#1d2a34;border:1px solid #34444f;border-radius:999px;padding:8px 14px;text-decoration:none;color:#eaf0f6;font-size:14px}.tenant-tabs a:hover,.tenant-tabs a[aria-current=page]{background:#3152bd;border-color:#6886ee}
@media(min-width:900px){.control-group>a{padding:22px 24px}}
@media(max-width:650px){header{font-size:14px}h1{font-size:24px}.tenant-overview{grid-template-columns:repeat(2,minmax(0,1fr))}.control-group>a{padding:17px 14px;gap:12px}.control-group small{font-size:12px}.control-heading{margin:20px 0}.tenant-section .grid{gap:12px}}
</style>
<style>
/* Compact tenant workspace; independent of the main shop theme. */
:root{--panel-bg:#17212a;--panel-surface:#1d2a35;--panel-border:#2b3a46;--panel-text:#e8eef4;--panel-muted:#93a5b5}
body{background:var(--panel-bg);color:var(--panel-text);font:14px/1.55 system-ui,-apple-system,"Segoe UI",sans-serif}
header,main{max-width:1100px}header{padding:18px 24px;border:0;gap:16px}header strong{display:flex;align-items:center;gap:10px;font-size:15px;letter-spacing:-.25px}header strong:before{content:'M';display:grid;place-items:center;width:32px;height:32px;background:#249df3;color:white;border-radius:10px;font-size:18px;font-weight:800;flex:none}header>a{font-size:12px;text-decoration:none;border:1px solid var(--panel-border);border-radius:24px;padding:8px 14px;background:#1b2833}
main{padding:14px 24px 50px}h1{font-size:25px;letter-spacing:-.6px;margin:8px 0 18px;font-weight:650}h2{font-size:18px;letter-spacing:-.3px}h3{font-size:15px}.muted,small{color:var(--panel-muted)}a{color:#75c0fc;text-decoration:none}a:hover{color:#b8e0ff}
.card{background:var(--panel-surface);border:1px solid var(--panel-border);border-radius:14px;padding:22px;box-shadow:none}.grid{gap:18px}.notice{background:#203648;border:1px solid #2a485e;border-radius:12px;font-size:13px}.tag{background:#224338;color:#7cdbb0;font-size:11px;padding:4px 9px}.error{background:#442b35;border-color:#67404b;color:#ffc6d1}
input:not([type=checkbox]),select,textarea{background:#17232e;border:1px solid #354754;border-radius:9px;padding:10px 12px;font-size:14px;color:var(--panel-text)}input[type=checkbox]{accent-color:#249df3}label{font-size:13px;font-weight:500}button,.button{background:#249df3;border-radius:9px;padding:10px 17px;font-size:13px;font-weight:600;line-height:1.4}button:hover:not(:disabled),.button:hover{background:#43acf5;color:white}input:focus-visible,select:focus-visible,textarea:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid #58b8fc;outline-offset:3px}
.tenant-overview{margin:0 0 20px;gap:12px}.tenant-overview>div{padding:16px 18px;background:#1d2c39;border:1px solid #304354;border-radius:12px}.tenant-overview>div:nth-child(2){background:#2a2d45;border-color:#3c405c}.tenant-overview>div:nth-child(3){background:#223444;border-color:#314b60}.tenant-overview>div:nth-child(4){background:#203b34;border-color:#305448}.tenant-overview strong{font-size:23px;font-weight:650;letter-spacing:-.5px}.tenant-overview small{font-size:12px;margin-top:3px;color:#aebfcd}
.tenant-tabs{border-bottom:1px solid var(--panel-border);margin-bottom:22px;padding:0 0 16px;gap:7px}.tenant-tabs a{padding:8px 15px;border:1px solid transparent;background:#202f3c;font-size:13px;white-space:nowrap}.tenant-tabs a:hover,.tenant-tabs a[aria-current=page]{background:#234665;color:#69beff;border-color:#2e5c7d}
#tenant-control{max-width:850px;margin:0 auto 26px}.control-heading{margin:18px 0}.control-heading h2{font-size:21px;margin:0 0 5px}.control-heading p{margin:0;font-size:13px}.control-group{border:1px solid var(--panel-border);border-radius:14px;background:var(--panel-surface);margin-bottom:14px}.control-group>a{grid-template-columns:34px minmax(0,1fr) 14px;padding:15px 18px;gap:12px;font-size:13px;font-weight:600;border-color:var(--panel-border)}.control-group>a:hover{background:#253746}.control-group small{font-size:11px;line-height:1.5;color:var(--panel-muted);margin-top:3px}.control-icon{width:34px;height:34px;border-radius:10px}.control-svg{width:21px;height:21px;display:block}.purple{background:#6a54cc}.green{background:#258568}.blue{background:#267db7}.orange{background:#b67435}.control-note{font-size:11px;text-align:center}.control-back{font-size:12px;margin:0 0 16px}
.table-wrap{border:1px solid var(--panel-border);border-radius:10px;margin-top:16px}table{font-size:13px}th{background:#192631;color:#91a6b7;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600}td,th{padding:13px 15px;border-color:var(--panel-border)}tbody tr:hover{background:#233440}tbody tr:last-child td{border-bottom:0}.tenant-section>.card>form[method=get]{display:flex;flex-wrap:wrap;gap:12px;align-items:end}.tenant-section>.card>form[method=get] label{flex:1;min-width:140px}
.tenant-preview-banner{max-width:1052px!important;margin:0 auto 14px!important;padding:10px 16px;font-size:11px;background:#1c3040;border-color:#2a4559}.tenant-preview-banner .inline{gap:16px;margin-top:4px}.tenant-preview-banner a{font-size:11px}
.soc-common-tabs{display:flex;flex-wrap:wrap;gap:8px;border-bottom:1px solid var(--panel-border);margin:0 0 20px;padding:0 0 12px}
.soc-common-tab{background:#1a2835;border:1px solid #2e4152;color:#9bb2c6;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .15s ease}
.soc-common-tab:hover{background:#233748;color:#fff}
.soc-common-tab.active{background:#249df3;border-color:#38a9f7;color:#fff;box-shadow:0 2px 8px rgba(36,157,243,.3)}
.soc-tab-content{display:none}
.soc-tab-content.active{display:block}
.soc-field-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(270px,1fr));gap:16px;margin-bottom:16px}
.soc-toggle-card{background:#162430;border:1px solid #2a3c4c;border-radius:10px;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:12px}
.soc-toggle-card .desc{font-size:12px;color:#8ba2b5;margin-top:2px}
.soc-switch{position:relative;display:inline-block;width:44px;height:24px;flex:none}
.soc-switch input{opacity:0;width:0;height:0}
.soc-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#2a3b4a;transition:.25s;border-radius:24px}
.soc-slider:before{position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background-color:white;transition:.25s;border-radius:50%}
.soc-switch input:checked+.soc-slider{background-color:#249df3}
.soc-switch input:checked+.soc-slider:before{transform:translateX(20px)}
.soc-badge{display:inline-block;padding:2px 7px;font-size:11px;font-weight:600;border-radius:6px;background:#243e35;color:#78dcb2}
</style>
@endpush
