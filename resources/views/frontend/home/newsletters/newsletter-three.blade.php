<div class="cta cta-horizontal cta-horizontal-box bg-primary subscriber-form-three">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <h3 class="cta-title text-white text-center subscriber-form-three-title">{{ config('settings.subcribetitle') ?? '' }}</h3>
        </div>
        <div class="row align-items-center justify-content-center mt-2">
            <div class="col-3xl-5col">
                <form method="POST" action="{{ route('frontend.subscriber.store') }}">
                    @csrf
                    <div class="input-group">
                        <input type="email" name="email" class="form-control form-control-white"
                            placeholder="Enter your Email Address" aria-label="Email Address"
                            value="{{ old('email') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-white-2" type="submit"><span>Subscribe</span><i
                                    class="icon-long-arrow-right"></i></button>
                        </div>
                    </div>
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>
    </div>
</div>
