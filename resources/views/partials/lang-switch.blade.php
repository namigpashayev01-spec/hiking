<div class="lang">
    <a href="{{ route('locale.switch', 'az') }}" class="{{ app()->getLocale() === 'az' ? 'active' : '' }}">AZ</a>
    <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
</div>
