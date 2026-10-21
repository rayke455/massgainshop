<svg class="control-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($name)
@case('settings')<path d="m9 3-1 3-3 1v4l3 1 1 3h4l1-3 3-1V7l-3-1-1-3Z" transform="translate(1 2)"/><circle cx="12" cy="11" r="3"/>@break
@case('provider')<rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/>@break
@case('wallet')<path d="M20 7H5a2 2 0 0 1 0-4h12v4M3 5v14a2 2 0 0 0 2 2h15V7M20 12h-5v5h5M17 14.5h.01"/>@break
@case('chart')<path d="M4 3v17h17M8 15v-4m5 4V7m5 8V4"/>@break
@case('users')<circle cx="9" cy="7" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 4a3 3 0 0 1 0 6m2 4a5 5 0 0 1 3 4v3"/>@break
@case('chat')<path d="M21 11a9 9 0 0 1-9 9H4l-3 2 2-6a9 9 0 1 1 18-5ZM7 9h9M7 13h6"/>@break
@case('globe')<circle cx="12" cy="12" r="9"/><ellipse cx="12" cy="12" rx="4" ry="9"/><path d="M3 12h18"/>@break
@case('promo')<path d="m4 20 16-16"/><circle cx="6.5" cy="6.5" r="3"/><circle cx="17.5" cy="17.5" r="3"/>@break
@default<rect x="4" y="3" width="16" height="18" rx="3"/><path d="M8 8h8M8 12h8M8 16h5"/>
@endswitch
</svg>
