@props(['text', 'search' => ''])

@if($search !== '')
{!! preg_replace('/('.preg_quote($search, '/').')/i', '<mark class="bg-yellow-200 dark:bg-yellow-600/60 px-0.5 rounded">$1</mark>', e($text)) !!}
@else
{{ $text }}
@endif
