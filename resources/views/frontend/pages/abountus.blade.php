@extends('layouts.frontend')
@section('title', $title)
@section('metatitle', $metatitle)
@section('metakeywords', $metakeywords)
@section('metadescription', $metadescription)
@section('content')
    <div class="page-content">
        <div class="container">
            <div class="product-details-top">
                <div class="row">
                    {!! config('settings.aboutcontent') ?? '' !!}
                </div>
            </div>
        </div>
    </div>
@endsection

