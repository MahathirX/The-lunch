<!-- Breadcrumbs -->
<div class="{{ config('settings.breadcrumb_container_size') == 1 ? 'container' : '' }}">
    <div id="page-header-three" class="page-header text-center mb-2">
        <div class="container">
            <div class="bread-inner">
                <div class="row">
                    <div class="col-12">
                        <ul class="bread-list d-flex px-3 align-items: center;">
                            @foreach ($breadcrumb as $title => $url)
                                @if ($loop->last)
                                    <div class="d-flex align-items-center">
                                        <li class="active"><i class="fa-solid fa-star"></i></li>
                                        <li class="active">{{ $title }}</li>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        <li><i class="fa-regular fa-star"></i></li>
                                        <li><a href="{{ $url }}">{{ $title }}</a></li>
                                    </div>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->
