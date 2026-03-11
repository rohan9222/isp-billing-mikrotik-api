@if (siteUrlSettings('site_logo'))
    @if (file_exists(public_path(siteUrlSettings('site_logo'))))
        <img class="me-2" style="width: 190px; height: 53px;" src="{{ asset(siteUrlSettings('site_logo')) }}" alt="logo"/>
    @else
        <img class="me-2" style="width: 190px; height: 53px;" src="{{ asset('img/logo.png') }}" alt="logo"/>
    @endif
@else
    @if (siteUrlSettings('site_icon'))
        <img class="me-2" src="{{ asset(siteUrlSettings('site_icon')) }}" alt="" width="40" />
        <span class="font-sans-serif text-success">{{ siteUrlSettings('site_name') ?? 'Code Pagol' }}</span>
    @else
        <span class="font-sans-serif text-success">{{ siteUrlSettings('site_name') ?? 'Code Pagol' }}</span>
    @endif
@endif