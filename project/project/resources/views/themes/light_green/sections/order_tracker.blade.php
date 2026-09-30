<!-- Order tracker section start: check an order's progress without logging in -->
<section class="zk-section zk-section--tight" id="track-order">
    <div class="container">
        <div class="zk-track zk-reveal">
            <div class="zk-track__intro">
                <span class="zk-eyebrow">@lang('Order status')</span>
                <h2 class="zk-title">{!! zkHighlight(__('Where is my [order]?')) !!}</h2>
                <p class="zk-lead">@lang('Type your order ID to see live progress. No login needed. You can find the ID in My orders.')</p>
            </div>
            <form class="zk-track__form" id="zkTrackForm" novalidate>
                <label for="zkTrackId" class="visually-hidden">@lang('Order ID')</label>
                <div class="zk-track__field">
                    <i class="fa-regular fa-hashtag" aria-hidden="true"></i>
                    <input type="text" id="zkTrackId" inputmode="numeric" autocomplete="off"
                           placeholder="@lang('Order ID, e.g. 1024')" maxlength="12" required>
                </div>
                <button type="submit" class="zk-btn zk-btn--primary">
                    <i class="fa-regular fa-magnifying-glass"></i> @lang('Track order')
                </button>
            </form>
            <div class="zk-track__result" id="zkTrackResult" aria-live="polite" hidden></div>
        </div>
    </div>
</section>

{{-- Inline: sections are rendered to HTML before the layout, so @push would be lost --}}
<script>
    "use strict";
    (function () {
        const form = document.getElementById('zkTrackForm');
        if (!form) return;
        const input = document.getElementById('zkTrackId');
        const out = document.getElementById('zkTrackResult');
        const btn = form.querySelector('button');
        const url = @json(route('order.track'));
        const ordersUrl = @json(route('user.order.index'));
        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]));
        const tones = {completed: 'ok', progress: 'run', processing: 'run', pending: 'wait', awaiting: 'wait', partial: 'warn', canceled: 'bad', refunded: 'bad', fail: 'bad'};

        const show = html => { out.innerHTML = html; out.hidden = false; };

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const id = input.value.replace(/\D/g, '');
            if (!id) {
                show('<p class="zk-track__msg zk-track__msg--bad"><i class="fa-regular fa-circle-exclamation"></i> ' + esc(@json(__('Enter the order ID first (numbers only).'))) + '</p>');
                input.focus();
                return;
            }
            btn.disabled = true;
            show('<p class="zk-track__msg"><i class="fa-regular fa-spinner-third fa-spin"></i> ' + esc(@json(__('Checking your order…'))) + '</p>');
            try {
                const res = await fetch(url + '?id=' + encodeURIComponent(id), {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
                if (res.status === 429) throw new Error(@json(__('Too many checks. Please wait a minute and try again.')));
                const d = await res.json();
                if (!d.found) {
                    show('<p class="zk-track__msg zk-track__msg--bad"><i class="fa-regular fa-circle-exclamation"></i> ' + esc(d.message) + '</p>');
                    return;
                }
                const tone = tones[d.status] || 'wait';
                const facts = [
                    [@json(__('Quantity')), d.quantity],
                    d.start_count !== null ? [@json(__('Start count')), d.start_count] : null,
                    d.remains !== null ? [@json(__('Remaining')), d.remains] : null,
                    [@json(__('Placed')), d.placed],
                    [@json(__('Last update')), d.updated],
                ].filter(Boolean).map(f => '<div><dt>' + esc(f[0]) + '</dt><dd>' + esc(f[1]) + '</dd></div>').join('');
                show(
                    '<div class="zk-track__card">' +
                        '<div class="zk-track__top">' +
                            '<div><small>#' + esc(d.id) + (d.platform ? ' · ' + esc(d.platform) : '') + '</small><strong>' + esc(d.service) + '</strong></div>' +
                            '<span class="zk-track__badge zk-track__badge--' + tone + '">' + esc(d.label) + '</span>' +
                        '</div>' +
                        '<div class="zk-track__bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="' + d.progress + '"><span style="width:' + d.progress + '%"></span></div>' +
                        '<p class="zk-track__hint">' + esc(d.hint) + '</p>' +
                        '<dl class="zk-track__facts">' + facts + '</dl>' +
                        (d.mine ? '<a class="zk-track__link" href="' + esc(ordersUrl) + '">' + esc(@json(__('Open in My orders'))) + ' <i class="fa-regular fa-arrow-right"></i></a>' : '') +
                    '</div>'
                );
            } catch (err) {
                show('<p class="zk-track__msg zk-track__msg--bad"><i class="fa-regular fa-wifi-exclamation"></i> ' + esc(err.message || @json(__('Could not check right now. Please try again.'))) + '</p>');
            } finally {
                btn.disabled = false;
            }
        });
    })();
</script>
<!-- Order tracker section end -->
