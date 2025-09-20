@extends('layouts.app')
@section('title', $title)
@section('add_button')
    <div>
        <a href="{{ route('admin.dashboard.notification.view') }}"
            class="create_btns  btn-md d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-eye me-2"></i>
            <span>{{ __f("See All Notification Btn Title") }}</span>
        </a>
    </div>
@endsection
@section('content')
    <div class="dashboard">
        <div class="row">
            <div class="col-xl-12 col-xxl-12 d-flex">
                <div class="w-100">
                    <div class="card p-2 top-card mb-2">
                        <div class="dashboard-title">
                            <div>
                                <h2>{{ $title ?? "" }}</h2>
                            </div>
                        </div>
                        <div class="bottom-card-wrapper">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __f('SL Title') }}</th>
                                            <th>{{ __f('Type Title') }} </th>
                                            <th>{{ __f('Title Title') }} </th>
                                            <th>{{ __f('Created At Title') }} </th>
                                            <th>{{ __f('Details Title') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>{{ 1 }}</th>
                                            <td>
                                                @if ($notification->data['type'] == 'new_order')
                                                    <span
                                                        class="badge bg-success">{{ __f('Notification New Order Title') }}</span>
                                                @else
                                                    <span
                                                        class="badge bg-primary">{{ __f('Notification New User Title') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $notification->data['title'] ?? '' }}</td>
                                            <td>{{ $notification->created_at->diffForHumans() ?? '' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                    {{ __f('Notification Modal Title') }}</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h4>{{ $notification->data['title'] ?? '' }}</h4>
                                                                {{ $notification->data['message'] ?? '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
