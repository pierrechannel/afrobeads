@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4 display-4 font-weight-bold">About Us</h1>
    <p class="lead text-center" style="font-size: 1.5rem;">Welcome to our universe! We are an enthusiastic team committed to delivering extraordinary products and services that empower our clients and foster enduring relationships.</p>

    <h2 class="mt-5 display-5 font-weight-bold">Our Mission</h2>
    <p style="font-size: 1.2rem;">At Afrobeads, our mission is unequivocal: to enhance the lives of our customers through innovative solutions, unparalleled service, and an unwavering commitment to quality. We firmly believe that customer satisfaction is the foundation of our success, and we endeavor to surpass expectations at every turn.</p>

    <h2 class="mt-5 display-5 font-weight-bold">Our Journey</h2>
    <p style="font-size: 1.2rem;">Founded in {{ date('Y') }}, we embarked on our journey with a vision to create a positive impact within our industry. Over the past [X years], we have continuously evolved, adapting and refining our services based on invaluable customer feedback and market trends. Our dedication to excellence has cultivated lasting relationships with our clients, who inspire us to innovate and improve relentlessly.</p>

    <h2 class="mt-5 display-5 font-weight-bold">Meet Our Team</h2>
    <p class="text-center" style="font-size: 1.2rem;">We take pride in our diverse team of professionals who bring passion and expertise to their distinct fields. Together, we collaborate, innovate, and propel our mission forward.</p>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="{{ asset('assets\img\card.jpg') }}" class="card-img-top" alt="John Doe" loading="lazy">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold" style="font-size: 1.5rem;">John Doe</h5>
                    <p class="card-text">Founder & CEO</p>
                    <p class="card-text text-muted" style="font-size: 1.1rem;">Leading the company with a vision for innovative growth and excellence in service.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="{{ asset('assets\img\card.jpg') }}" class="card-img-top" alt="Jane Smith" loading="lazy">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold" style="font-size: 1.5rem;">Jane Smith</h5>
                    <p class="card-text">Chief Operating Officer</p>
                    <p class="card-text text-muted" style="font-size: 1.1rem;">Ensuring smooth operations and exceptional service delivery across all teams.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="{{ asset('assets\img\card.jpg') }}" class="card-img-top" alt="Mike Johnson" loading="lazy">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold" style="font-size: 1.5rem;">Mike Johnson</h5>
                    <p class="card-text">Head of Marketing</p>
                    <p class="card-text text-muted" style="font-size: 1.1rem;">Crafting compelling narratives and strategies that resonate with our audience.</p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mt-5 display-5 font-weight-bold">Get in Touch</h2>
    <p style="font-size: 1.2rem;">If you have any inquiries or wish to connect with us, please <a href="/contact">reach out</a>. We would love to hear from you!</p>
</div>

@push('scripts')
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
