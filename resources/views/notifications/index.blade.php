<x-template title="Notifications">
    <div class="container py-3">
        <ul class="list-group">
            @foreach(auth()->user()->notifications as $notification)
                <a @class(['list-group-item', 'list-group-item-action', 'text-muted opacity-50' => $notification->read_at]) href="{{ route('notification.read', ['id' => $notification->id]) }}">
                    {{ $notification->data['text'] }}
                </a>
            @endforeach
        </ul>
    </div>
</x-template>
