{{-- Smart link checker + "how to copy your link" guide for the New order page. Markup lives next to #link in add.blade.php. --}}
@push('style')
    <style>
        .zk-linkstatus { display: flex; gap: 8px; align-items: flex-start; margin-top: 8px; font-size: 13.5px; line-height: 1.45; }
        .zk-linkstatus i { margin-top: 2px; }
        .zk-linkstatus--ok { color: #15803D; }
        .zk-linkstatus--warn { color: #B45309; }
        .zk-linkstatus--error { color: #B42318; }
        .zk-linkstatus__fix { border: 0; background: none; padding: 0; color: var(--zk-primary); font-weight: 700; text-decoration: underline; }
        #link.zk-link--error { border-color: #F04438 !important; box-shadow: 0 0 0 4px #FEE4E2 !important; }
        #link.zk-link--ok { border-color: #22C55E !important; }
        .zk-linkhelp { margin-top: 10px; border: 1px solid var(--zk-line); border-radius: 12px; background: var(--zk-soft); }
        .zk-linkhelp__toggle { width: 100%; display: flex; align-items: center; gap: 8px; padding: 10px 14px; border: 0; background: none; font-weight: 700; color: var(--zk-ink); font-size: 14px; text-align: left; }
        .zk-linkhelp__toggle i:first-child { color: var(--zk-primary); }
        .zk-linkhelp__chev { margin-left: auto; transition: transform .2s; }
        .zk-linkhelp__toggle[aria-expanded="true"] .zk-linkhelp__chev { transform: rotate(180deg); }
        .zk-linkhelp__guide { padding: 0 14px 14px; font-size: 14px; }
        .zk-linkhelp__guide ol { padding-left: 18px; margin: 0 0 10px; display: grid; gap: 4px; }
        .zk-linkhelp__guide code { background: #fff; border: 1px solid var(--zk-line); border-radius: 6px; padding: 1px 6px; font-size: 12.5px; color: var(--zk-ink); word-break: break-all; }
        .zk-linkhelp__tips { margin: 0; padding-left: 18px; color: var(--zk-muted); font-size: 13px; }
    </style>
@endpush

@php
    $zkLinkText = [
                'empty' => __('Paste the link to your profile or post.'),
                'spaces' => __('The link has spaces in it. Copy it again with the app\'s Share → Copy link button.'),
                'two' => __('It looks like two links were pasted. Keep only one.'),
                'notLink' => __('This doesn\'t look like a link. Open the app, tap Share → Copy link, then paste it here.'),
                'wrongPlatform' => __('This link is from :found, but the service you picked is for :expected. Paste your :expected link instead, or switch to :found services above.'),
                'needProfile' => __('Followers/subscribers go to your profile, so paste your profile link (like :example), not a single post.'),
                'needPost' => __('Likes/views/comments go to one post or video. Open that post, tap Share → Copy link, and paste it here (like :example).'),
                'fixed' => __('Use this link'),
                'username' => __('That looks like a username, not a link.'),
                'okProfile' => __(':platform profile link — looks good.'),
                'okPost' => __(':platform post link — looks good.'),
                'ok' => __(':platform link — looks good.'),
                'private' => __('Make sure the account is public, otherwise the order cannot be delivered.'),
                'blocked' => __('Please fix the link first.'),
            ];
@endphp
@push('script')
    <script>
        'use strict';
        (function () {
            const T = @json($zkLinkText);

            // Domains → platform, and how profile vs post URLs look on each.
            const P = {
                Instagram: {hosts: ['instagram.com', 'instagr.am'], post: /\/(p|reel|reels|tv|stories)\//i, profileEx: 'instagram.com/yourname', postEx: 'instagram.com/p/C8xYz…',
                    steps: ['Open Instagram and go to the profile or post.', 'Tap ⋯ (top right) → Copy link. For a profile: tap ≡ → Share profile → Copy link.', 'Come back here and paste it.']},
                TikTok: {hosts: ['tiktok.com', 'vm.tiktok.com', 'vt.tiktok.com'], post: /\/video\/|\/photo\/|^\/?[A-Za-z0-9]{7,12}\/?$/i, shortIsPost: ['vm.tiktok.com', 'vt.tiktok.com'], profileEx: 'tiktok.com/@yourname', postEx: 'tiktok.com/@you/video/73…',
                    steps: ['Open TikTok and go to the video or your profile.', 'Tap Share (arrow icon) → Copy link.', 'Paste it here.']},
                YouTube: {hosts: ['youtube.com', 'youtu.be', 'm.youtube.com', 'music.youtube.com'], post: /\/(watch|shorts|live)\b|^youtu\.be/i, profileEx: 'youtube.com/@yourchannel', postEx: 'youtube.com/watch?v=… or youtu.be/…',
                    steps: ['Open the video (or your channel page).', 'Tap Share → Copy link.', 'Paste it here. For subscribers use your channel link: youtube.com/@yourchannel.']},
                Facebook: {hosts: ['facebook.com', 'fb.com', 'fb.watch', 'm.facebook.com'], post: /\/(posts|videos|reel|watch|photo|permalink|story)|fb\.watch|story_fbid|\/share\/[prv]\//i, profileEx: 'facebook.com/yourpage', postEx: 'facebook.com/yourpage/posts/…',
                    steps: ['Open the page or post.', 'Tap Share → Copy link (on a page: ⋯ → Copy link).', 'Paste it here.']},
                Telegram: {hosts: ['t.me', 'telegram.me'], post: /^\/?[^/]+\/\d+/, profileEx: 't.me/yourchannel', postEx: 't.me/yourchannel/123',
                    steps: ['Open the channel or group (it must be public).', 'Tap the name at the top → copy the t.me link. For a post: long-press it → Copy link.', 'Paste it here.']},
                Twitter: {hosts: ['twitter.com', 'x.com', 'mobile.twitter.com'], post: /\/status\//i, profileEx: 'x.com/yourname', postEx: 'x.com/you/status/18…',
                    steps: ['Open the post or profile on X (Twitter).', 'Tap Share → Copy link.', 'Paste it here.']},
                Spotify: {hosts: ['open.spotify.com', 'spotify.link'], post: /\/(track|album|playlist|episode)\//i, profileEx: 'open.spotify.com/artist/…', postEx: 'open.spotify.com/track/…',
                    steps: ['Open the song, playlist or artist.', 'Tap ⋯ → Share → Copy link.', 'Paste it here.']},
                LinkedIn: {hosts: ['linkedin.com', 'lnkd.in'], post: /\/(posts|feed\/update|pulse)\//i, profileEx: 'linkedin.com/in/yourname', postEx: 'linkedin.com/posts/…',
                    steps: ['Open the post or profile.', 'Tap ⋯ → Copy link to post (or Share profile).', 'Paste it here.']},
                Threads: {hosts: ['threads.net', 'threads.com'], post: /\/post\//i, profileEx: 'threads.net/@yourname', postEx: 'threads.net/@you/post/…',
                    steps: ['Open the thread or profile.', 'Tap Share → Copy link.', 'Paste it here.']},
                Twitch: {hosts: ['twitch.tv'], post: /\/videos\/|\/clip\//i, profileEx: 'twitch.tv/yourname', postEx: 'twitch.tv/videos/…', steps: ['Open your channel.', 'Copy the address from the browser.', 'Paste it here.']},
                Discord: {hosts: ['discord.gg', 'discord.com'], post: /$^/, profileEx: 'discord.gg/invite', postEx: '', steps: ['Open your server → Invite people.', 'Set the invite to never expire → Copy.', 'Paste it here.']},
                Snapchat: {hosts: ['snapchat.com'], post: /\/spotlight\//i, profileEx: 'snapchat.com/add/yourname', postEx: 'snapchat.com/spotlight/…', steps: ['Open your profile → Share → Copy link.', 'Paste it here.']},
            };
            const ALIASES = {X: 'Twitter', 'X (Twitter)': 'Twitter', 'Twitter/X': 'Twitter', Youtube: 'YouTube', Tiktok: 'TikTok'};

            const PROFILE_WORDS = /follower|subscriber|member|friend|page like|profile visit|channel/i;
            const POST_WORDS = /like|view|comment|share|save|retweet|repost|impression|reach|watch|play|stream|reaction|vote|poll/i;

            const input = document.getElementById('link');
            if (!input) return;
            const form = input.closest('form');
            const help = document.getElementById('zkLinkHelp');
            const guide = document.getElementById('zkLinkGuide');
            const toggle = help.querySelector('.zk-linkhelp__toggle');
            const status = document.getElementById('zkLinkStatus');

            const fmt = (s, o) => s.replace(/:(\w+)/g, (_, k) => o[k] ?? '');
            const esc = s => String(s).replace(/[&<>"]/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;'}[c]));
            const selectedText = sel => { const el = document.querySelector(sel); return el && el.selectedIndex > 0 ? el.options[el.selectedIndex].text.trim() : ''; };

            function expectedPlatform() {
                const btn = document.querySelector('.social-btn.activeSocialMedia');
                const text = [btn ? btn.textContent : '', selectedText('.cmn-select3'), selectedText('.cmn-select4')].join(' ');
                for (const name of Object.keys(P)) {
                    if (new RegExp('\\b' + name + '\\b', 'i').test(text)) return name;
                }
                for (const [alias, name] of Object.entries(ALIASES)) {
                    if (new RegExp('\\b' + alias.replace(/[()]/g, '\\$&') + '\\b', 'i').test(text)) return name;
                }
                return null;
            }

            function wantedKind() {
                const text = selectedText('.cmn-select4') + ' ' + selectedText('.cmn-select3');
                if (PROFILE_WORDS.test(text)) return {kind: 'profile', what: (text.match(PROFILE_WORDS) || ['Followers'])[0]};
                if (POST_WORDS.test(text)) return {kind: 'post', what: (text.match(POST_WORDS) || ['Likes'])[0]};
                return null;
            }

            function platformOf(host) {
                host = host.replace(/^www\./, '').toLowerCase();
                for (const [name, p] of Object.entries(P)) {
                    if (p.hosts.some(h => host === h || host.endsWith('.' + h))) return name;
                }
                return null;
            }

            function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1).toLowerCase(); }

            // Returns {level: ok|warn|error|none, msg, fix?}
            function check(raw) {
                let v = (raw || '').trim();
                if (!v) return {level: 'none'};
                if (!/[./]/.test(v) && !/^@?[A-Za-z0-9._]{2,30}$/.test(v)) return {level: 'error', msg: T.notLink};
                if (/\s/.test(v)) return {level: 'error', msg: T.spaces};
                if ((v.match(/https?:\/\//g) || []).length > 1) return {level: 'error', msg: T.two};

                const exp = expectedPlatform();
                // "@username" or bare "username" → offer the full profile link for the chosen platform.
                if (/^@?[A-Za-z0-9._]{2,30}$/.test(v) && exp && P[exp]) {
                    const user = v.replace(/^@/, '');
                    const base = {Instagram: 'https://www.instagram.com/', TikTok: 'https://www.tiktok.com/@', YouTube: 'https://www.youtube.com/@', Twitter: 'https://x.com/', Telegram: 'https://t.me/', Threads: 'https://www.threads.net/@', Twitch: 'https://www.twitch.tv/'}[exp];
                    if (base) return {level: 'error', msg: T.username, fix: base + user};
                }

                let url;
                try { url = new URL(/^https?:\/\//i.test(v) ? v : 'https://' + v); } catch (e) { return {level: 'error', msg: T.notLink}; }
                if (!/\.[a-z]{2,}$/i.test(url.hostname)) return {level: 'error', msg: T.notLink};

                const found = platformOf(url.hostname);
                const fixUrl = !/^https?:\/\//i.test(v) ? url.href : null;

                if (exp && found && found !== exp) {
                    return {level: 'error', msg: fmt(T.wrongPlatform, {found, expected: exp})};
                }
                const platform = found || exp;
                const p = P[platform];
                const want = wantedKind();
                if (p && found && want) {
                    const path = url.hostname.replace(/^www\./, '') + url.pathname;
                    const isPost = p.post.test(url.pathname) || p.post.test(path) || (p.shortIsPost || []).includes(url.hostname.replace(/^www\./, ''));
                    if (want.kind === 'profile' && isPost) return {level: 'error', msg: fmt(T.needProfile, {what: cap(want.what), example: p.profileEx})};
                    if (want.kind === 'post' && !isPost && p.postEx) return {level: 'error', msg: fmt(T.needPost, {what: cap(want.what), example: p.postEx})};
                }

                // Strip tracking junk that apps add when sharing (igsh, si, utm_*), keep YouTube's v=.
                let clean = url;
                ['igsh', 'igshid', 'si', 'feature', 'fbclid', 'share_id', 'utm_source', 'utm_medium', 'utm_campaign', 'is_from_webapp', 'sender_device', '_r', '_t'].forEach(k => clean.searchParams.delete(k));
                const cleaned = clean.href.replace(/\?$/, '');
                const fix = (fixUrl || cleaned !== v) ? cleaned : null;

                if (!found) return {level: 'warn', msg: fmt(T.ok, {platform: url.hostname.replace(/^www\./, '')}) + ' ' + T.private, fix};
                const kind = want ? (want.kind === 'profile' ? T.okProfile : T.okPost) : T.ok;
                return {level: 'ok', msg: fmt(kind, {platform}) + ' ' + T.private, fix};
            }

            let last = {level: 'none'};
            function render() {
                last = check(input.value);
                input.classList.toggle('zk-link--error', last.level === 'error');
                input.classList.toggle('zk-link--ok', last.level === 'ok');
                if (last.level === 'none') { status.innerHTML = ''; }
                else {
                    const icon = {ok: 'fa-circle-check', warn: 'fa-triangle-exclamation', error: 'fa-circle-exclamation'}[last.level];
                    status.className = 'zk-linkstatus zk-linkstatus--' + last.level;
                    status.innerHTML = `<i class="fa-regular ${icon}"></i><span>${esc(last.msg)}${last.fix ? ` <button type="button" class="zk-linkstatus__fix">${esc(T.fixed)}: ${esc(last.fix.length > 48 ? last.fix.slice(0, 48) + '…' : last.fix)}</button>` : ''}</span>`;
                    const btn = status.querySelector('.zk-linkstatus__fix');
                    if (btn) btn.addEventListener('click', () => { setValue(last.fix); });
                }
                renderGuide();
            }

            function setValue(v) {
                input.value = v;
                input.dispatchEvent(new Event('input', {bubbles: true})); // keep Vue's v-model in sync
                render();
            }

            function renderGuide() {
                const name = expectedPlatform() || platformOf((() => { try { return new URL(/^https?:/i.test(input.value) ? input.value : 'https://' + input.value).hostname; } catch (e) { return ''; } })());
                const p = P[name];
                const want = wantedKind();
                if (!p) {
                    guide.innerHTML = `<ol><li>{{ __('Open the app and go to the profile or post you want to grow.') }}</li><li>{{ __('Tap Share (or ⋯) → Copy link.') }}</li><li>{{ __('Paste it in the box above.') }}</li></ol>`;
                    return;
                }
                const example = want && want.kind === 'post' ? p.postEx : p.profileEx;
                guide.innerHTML = `<p class="mb-2"><strong>${esc(name)}</strong>${want ? ' · ' + esc(want.kind === 'profile' ? @json(__('profile link needed')) : @json(__('post link needed'))) : ''}</p>
                    <ol>${p.steps.map(s => `<li>${esc(s)}</li>`).join('')}</ol>
                    ${example ? `<p class="mb-2">{{ __('It should look like') }} <code>${esc(example)}</code></p>` : ''}
                    <ul class="zk-linkhelp__tips"><li>{{ __('The account must be public while the order runs.') }}</li><li>{{ __('Don\'t change your username until the order is complete.') }}</li><li>{{ __('One link per order. For many links use Mass order.') }}</li></ul>`;
            }

            toggle.addEventListener('click', () => {
                const open = guide.hidden;
                guide.hidden = !open;
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) renderGuide();
            });

            let t;
            input.addEventListener('input', () => { clearTimeout(t); t = setTimeout(render, 250); });
            input.addEventListener('blur', render);
            input.addEventListener('paste', () => setTimeout(render, 0));
            // Re-check when the platform, category or service changes.
            document.addEventListener('click', e => { if (e.target.closest('.social-btn')) setTimeout(render, 300); });
            $(document).on('change', '.cmn-select3, .cmn-select4', () => setTimeout(render, 50));

            // Block submitting a link we know is wrong (runs before Vue's submit handler).
            form.addEventListener('submit', e => {
                render();
                if (last.level === 'error' || last.level === 'none') {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    if (last.level === 'none') {
                        status.className = 'zk-linkstatus zk-linkstatus--error';
                        status.innerHTML = `<i class="fa-regular fa-circle-exclamation"></i><span>${esc(T.empty)}</span>`;
                        input.classList.add('zk-link--error');
                    }
                    input.focus();
                    input.scrollIntoView({block: 'center', behavior: 'smooth'});
                }
            }, true);

            render();
        })();
    </script>
@endpush
