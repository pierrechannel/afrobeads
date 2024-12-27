@extends('layouts.app')

@section('title', 'Current Deals')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Exclusive Deals Just for You!</h1>
    <p class="text-center lead">Check out our current promotions and save big on your purchase!</p>

    <div class="row mt-4">
        @foreach($deals as $deal)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="{{ asset($deal['image']) }}" class="card-img-top" alt="{{ $deal['title'] }}" loading="lazy">
                <div class="card-body">
                    <h5 class="card-title">{{ $deal['title'] }}</h5>
                    <p class="card-text">{{ $deal['description'] }}</p>
                    <p class="text-muted">Expires on: <strong>{{ $deal['expires'] }}</strong></p>
                    <a href="#" class="btn btn-primary">Claim Deal</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endsection