@php
    $message = [
        'type' => '',
        'text' => '',
        'title' => '',
        'bg_color' => '',
        'border_color' => '',
        'text_color' => '',
        'icon_color' => '',
    ];

    if (session()->has('success')) {
        $message['type'] = 'success';
        $message['text'] = session('success');
        $message['title'] = 'Success!';
        $message['bg_color'] = 'bg-green-100';
        $message['border_color'] = 'border-green-400';
        $message['text_color'] = 'text-green-700';
        $message['icon_color'] = 'text-green-500';
    } elseif (session()->has('error')) {
        $message['type'] = 'error';
        $message['text'] = session('error');
        $message['title'] = 'Error!';
        $message['bg_color'] = 'bg-red-100';
        $message['border_color'] = 'border-red-400';
        $message['text_color'] = 'text-red-700';
        $message['icon_color'] = 'text-red-500';
    } elseif ($errors->any()) {
        $message['type'] = 'validation';
        $message['text'] = 'There were some problems with your input.';
        $message['title'] = 'Whoops!';
        $message['bg_color'] = 'bg-red-100';
        $message['border_color'] = 'border-red-400';
        $message['text_color'] = 'text-red-700';
        $message['icon_color'] = 'text-red-500';
    }
@endphp

@if ($message['type'])
    <div class="mb-4 {{ $message['bg_color'] }} {{ $message['border_color'] }} {{ $message['text_color'] }} px-4 py-3 rounded relative"
        role="alert">
        <strong class="font-bold">{{ $message['title'] }}</strong>
        <span class="block sm:inline">{{ $message['text'] }}</span>

        @if ($message['type'] === 'validation')
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3"
            onclick="this.closest('[role=alert]').style.display='none';">
            <svg class="fill-current h-6 w-6 {{ $message['icon_color'] }}" role="button"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
            </svg>
        </button>
    </div>
@endif
