<div class="container">
    <form wire:submit.prevent="submitComment">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label><span class="text-danger">*</span>
            <input type="text" class="form-control form-control-sm" wire:model="name" id="name" placeholder="Enter your name">
            <x-input-error for='name' />
        </div>
        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label><span class="text-danger">*</span>
            <input type="text" class="form-control form-control-sm" wire:model="mobile" id="mobile" placeholder="Enter your mobile number">
            <x-input-error for='mobile' />
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label><span class="text-danger">*</span>
            <input type="email" class="form-control form-control-sm" wire:model="email" id="email" placeholder="name@example.com">
            <x-input-error for='email' />
        </div>
        <div class="mb-3">
            <label for="comment" class="form-label">Comment</label><span class="text-danger">*</span>
            <textarea class="form-control form-control-sm" wire:model="comment" id="comment" rows="3"></textarea>
            <x-input-error for='comment' />
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>


    {{-- <button wire:click="showComments" class="btn btn-info mt-3">Show Others Comments</button>
    @if ($allComments)
        <ul class="list-group mt-3">
            @foreach ($allComments as $comment)
                <li class="list-group-item mb-2">
                    <strong>{{ $comment->name }}</strong><br>
                    {{ $comment->comment }}<br>
                    <small>{{ $comment->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
    @endif --}}
</div>
