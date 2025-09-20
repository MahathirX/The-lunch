@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="dashboard">
        <div class="row">
            <div class="col-xl-12 col-xxl-12 d-flex">
                <div class="w-100">
                    <div class="card p-2 top-card mb-2">
                        <div class="dashboard-title">
                            <div>
                                <h2>{{ __f('Unread Notifications Title') }}</h2>
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
                                            <th class="text-end">{{ __f('Actions Title') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (Auth::user()->unreadNotifications && count(Auth::user()->unreadNotifications) > 0)
                                            @forelse (Auth::user()->unreadNotifications as $key => $notification)
                                                <tr>
                                                    <th>{{ $key + 1 ?? 1 }}</th>
                                                    <td>
                                                        @if ($notification->data['type'] == 'new_order')
                                                            <span
                                                                class="badge bg-success">{{ __f('Notification New Order Title') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-primary">{{ __f('Notification New User Title') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $notification->data['title'] ?? 'New Notification' }}</td>
                                                    <td>{{ $notification->created_at->diffForHumans() ?? '' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#ReadNotification{{ $notification->id ?? '' }}">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade"
                                                            id="ReadNotification{{ $notification->id ?? '' }}"
                                                            tabindex="-1"
                                                            aria-labelledby="ReadNotification{{ $notification->id ?? '' }}Label"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="ReadNotification{{ $notification->id ?? '' }}Label">
                                                                            {{ __f('Notification Modal Title') }}</h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h4>{{ $notification->data['title'] ?? '' }}</h4>
                                                                        {{ $notification->data['message'] ?? '' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <div>
                                                            <a class="btn btn-sm btn-primary text-white" href="{{ route('admin.dashboard.notification.read',['id' => $notification->id]) }}"><i class="fa-solid fa-eye me-2"></i>{{ __f('Read Title') }}</a>
                                                            {{-- <button class="btn btn-sm btn-danger text-white" onclick="delete_data({{ $notification->id ?? '' }})">
                                                            <i class="fa-solid fa-trash me-2 text-white"></i>{{ __f('Delete Title') }}</button>
                                                        <form
                                                            action="{{ route('admin.dashboard.notification.delete',['id' => $notification->id ]) }}"
                                                            id="delete-form-{{ $notification->id ?? '' }}" method="DELETE"
                                                            class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form> --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <p class="text-danger text-center">
                                                            {{ __f('No Notification Message') }}</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="6">
                                                    <p class="text-danger text-center">{{ __f('No Notification Message') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card p-2 top-card mb-2">
                        <div class="dashboard-title">
                            <div>
                                <h2>{{ __f('Read Notifications Title') }}</h2>
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
                                            <th class="text-end">{{ __f('Details Title') }} </th>
                                            {{-- <th class="text-end">{{ __f('Actions Title') }} </th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (Auth::user()->readNotifications && count(Auth::user()->readNotifications) > 0)
                                            @forelse (Auth::user()->readNotifications as $key => $notification)
                                                <tr>
                                                    <th>{{ $key + 1 ?? 1 }}</th>
                                                    <td>
                                                        @if ($notification->data['type'] == 'new_order')
                                                            <span
                                                                class="badge bg-success">{{ __f('Notification New Order Title') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-primary">{{ __f('Notification New User Title') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $notification->data['title'] ?? 'New Notification' }}</td>
                                                    <td>{{ $notification->created_at->diffForHumans() ?? '' }}</td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#ReadNotification{{ $notification->id ?? '' }}">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade"
                                                            id="ReadNotification{{ $notification->id ?? '' }}"
                                                            tabindex="-1"
                                                            aria-labelledby="ReadNotification{{ $notification->id ?? '' }}Label"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="ReadNotification{{ $notification->id ?? '' }}Label">
                                                                            {{ __f('Notification Modal Title') }}</h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-start">
                                                                        <h4>{{ $notification->data['title'] ?? '' }}</h4>
                                                                        {{ $notification->data['message'] ?? '' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    {{-- <td class="text-end">
                                                        <button class="btn btn-sm btn-danger text-white" onclick="delete_data({{ $notification->id  ?? ''}})">
                                                            <i class="fa-solid fa-trash me-2 text-white"></i>{{ __f('Delete Title') }}</button>
                                                        <form
                                                            action="{{ route('admin.dashboard.notification.delete',['id' => $notification->id ]) }}"
                                                            id="delete-form-{{ $notification->id  ?? ''}}" method="DELETE"
                                                            class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td> --}}
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5">
                                                        <p class="text-danger text-center">
                                                            {{ __f('No Notification Message') }}</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="5">
                                                    <p class="text-danger text-center">{{ __f('No Notification Message') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endif
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
