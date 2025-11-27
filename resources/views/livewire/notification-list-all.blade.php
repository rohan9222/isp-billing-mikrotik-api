<div class="card zoom-in">
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('All Notification List') }}
        </h2>
    </x-slot>

    <div class="card-body fs-10 p-3">
        @foreach ($notifications as $notification)
            <div class="border-bottom-0 notification rounded-0 border-x-0 border-300">
                <div class="notification-avatar">
                    <div class="avatar avatar-xl me-3">
                        <img class="rounded-circle" src="{{ generate_avatar($notification->title) }}" alt="{{$notification->title}}" />
                    </div>
                </div>
                <div class="notification-body">
                    <p class="mb-1"><strong>{{$notification->title}}</strong> Message :
                        {{ $notification->message }}
                    </p>
                    <span class="notification-time"><span class="me-2" role="img" aria-label="Emoji">💬</span>{{$notification->created_at->diffForHumans()}}</span>
                </div>
            </div>
        @endforeach

        @if ($notifications->isEmpty())
            <div class="text-center text-muted">
                <p>{{ __('No notifications found.') }}</p>
            </div>
        @endif

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
