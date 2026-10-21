@push('styles')
<style>
.owner-bottom-nav{display:none}
.owner-orders-screen{background:#172127;color:#e4e9ec;padding-bottom:100px}
.owner-orders-screen header{padding:10px 16px 18px;gap:12px;flex-wrap:nowrap}
.owner-orders-screen header strong{border:1px solid #344149;border-radius:28px;padding:6px 14px 6px 7px;font-size:15px;line-height:26px;display:flex;align-items:center;gap:10px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}
.owner-orders-screen header strong:before{width:26px;height:26px;border-radius:8px;font-size:14px;background:#d94d67;flex-shrink:0}
.owner-orders-screen header>a{font-size:12px;padding:8px 12px;border-radius:24px}
.owner-orders-screen main{padding:0 16px 24px}
.owner-orders-screen .owner-page-heading,.owner-orders-screen .tenant-overview,.owner-orders-screen .tenant-tabs,.owner-orders-screen .owner-panel-meta,.owner-orders-screen #control-back{display:none!important}
.owner-orders-screen .tenant-preview-banner{padding:5px 12px;margin:0 auto 12px;font-size:11px;background:transparent;border:0;color:#93a5b0}
.owner-orders-screen .tenant-preview-banner .inline{display:none}
.owner-order-search{position:relative;display:flex;align-items:center;gap:10px;background:#22313b;border-radius:28px;padding:0 15px;height:47px;margin:4px 0 16px}
.owner-order-search>svg{width:21px;height:21px;flex-shrink:0;color:#8b9ba7}
.owner-order-search input:not([type=hidden]){background:transparent;border:0!important;box-shadow:none;padding:10px 0;color:#e4e9ec;min-width:0}
.owner-order-search input::placeholder{color:#91a1ae;opacity:1}
.owner-order-search button{margin:0;padding:5px 8px;background:transparent;color:#62b9f0;font-size:12px;border-radius:20px}
.owner-order-search:focus-within{outline:2px solid #459cd4;outline-offset:2px}
.owner-order-statuses{display:flex;gap:7px;overflow-x:auto;white-space:nowrap;padding:0 0 12px;margin-bottom:4px;scrollbar-width:thin;scrollbar-color:#384b58 transparent}
.owner-order-statuses a{flex-shrink:0;padding:9px 14px;background:#22313b;border-radius:24px;color:#aebbc4;font-size:13px;text-decoration:none}
.owner-order-statuses a[aria-current]{color:#61baf6;background:#213748}
.owner-order-row{border-bottom:1px solid #2b3841}
.owner-order-row summary{display:flex;align-items:center;gap:13px;min-height:91px;cursor:pointer;list-style:none;padding:13px 0}
.owner-order-row summary::-webkit-details-marker{display:none}
.owner-service-icon{display:grid;place-items:center;width:45px;height:45px;background:#22313b;border-radius:12px;flex-shrink:0;color:#d3dce2}
.owner-service-icon svg{width:23px;height:23px}
.owner-order-copy{min-width:0;flex:1;display:flex;flex-direction:column;gap:3px;font-size:13px;line-height:1.5}
.owner-order-title{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:14px;color:#e4e9ec}
.owner-service-id{background:#2a3943;color:#a4b2bc;font-size:10px;border-radius:4px;padding:2px 4px;margin-right:3px}
.owner-order-progress{color:#b8c5cc}.order-state{color:#58b4f0}.order-complete{color:#51cda0}
.owner-order-price{color:#8ea0ad;font-size:12px}.owner-order-price .order-complete{margin-left:8px}
.owner-order-chevron{color:#91a3af;font-size:25px}.owner-order-row[open] .owner-order-chevron{transform:rotate(90deg)}
.owner-order-detail{padding:0 14px 18px 58px;color:#9fb0bd;font-size:13px;overflow-wrap:anywhere}
.owner-orders-empty{text-align:center;color:#91a3af;padding:60px 16px}.owner-orders-empty>svg{width:38px;height:38px}.owner-orders-empty h2{font-size:17px;color:#d6e0e7}.owner-orders-empty p{font-size:13px}
.owner-guest-orders{margin-top:20px;color:#a4b5bf;font-size:13px}.owner-guest-orders>summary{cursor:pointer;padding:12px 0}
.owner-orders-screen .owner-bottom-nav{position:fixed;display:flex;bottom:20px;left:50%;transform:translateX(-50%);width:calc(100% - 32px);max-width:700px;z-index:20;background:#22313b;border:1px solid #30414e;border-radius:18px;padding:4px;box-shadow:0 8px 24px #0003;padding-bottom:max(4px,env(safe-area-inset-bottom))}
.owner-bottom-nav a{display:flex;flex:1;min-width:0;flex-direction:column;align-items:center;gap:2px;padding:5px 2px;color:#a9bac7;text-decoration:none;font-size:11px;line-height:15px;border-radius:13px}
.owner-bottom-nav a svg{width:20px;height:20px;fill:currentColor}.owner-bottom-nav a[aria-current]{color:#56b5f4;background:#293e4c}
.owner-order-statuses a:focus-visible,.owner-bottom-nav a:focus-visible,.owner-order-row summary:focus-visible{outline:2px solid #62b9f0;outline-offset:2px}
@media(max-width:380px){.owner-orders-screen header strong{font-size:12px;gap:6px}.owner-orders-screen header>a{font-size:10px;padding:7px}.owner-order-price .order-complete{display:block;margin-left:0}}
</style>
@endpush
