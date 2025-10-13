<div class="card">
    <div class="card-header bg-success-subtle">
        <h4 class="box-shadow text-center h4">{{ $title ?? '' }}</h4>
    </div>
    <div class="card-body {{ $class ?? '' }}" {{ $attributes ?? '' }}>
        {{ $aside ?? '' }}
    </div>
</div>


{{-- <div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url({{asset('images/corner-4.png')}});">
    </div><!--/.bg-holder-->
    <div class="card-body position-relative">
        <div class="row">
            <div class="col-xxl-8 col-md-12">
                
            </div>
        </div>
    </div>
</div> --}}