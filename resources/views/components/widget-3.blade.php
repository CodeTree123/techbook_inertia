@props([
    'link' => '',
    'title' => '',
    'value' => '',
    'icon' => '',
    'bg' => 'primary',
    'color' => 'white',
])

<div class="widget-two style-two box-shadow2 b-radius-5 bg-{{ $bg }}">
    <div class="widget-two__icon b-radius-5 bg-{{ $bg }}">
        <i class="{{ $icon }}"></i>
    </div>

    <div class="widget-two_content">
        <h3 class="text-{{ $color }}">{{ $value }}</h3>
        <p class="text-{{ $color }}">{{ __($title) }}</p>
    </div>
    @if ($link)
        <a href="{{ $link }}" class="widget-two_btn">@lang('View All')</a>
    @endif
</div>
