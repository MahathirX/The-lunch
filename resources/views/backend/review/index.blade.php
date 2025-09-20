@extends('layouts.app')
@section('title', $title)
@section('add_button')
    
@endsection
@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card px-3 py-3">
            <div class="bg-white border-bottom-0 pb-4 d-flex justify-content-between align-items-center flex-row">
                <h2 class="backend-title">{{ $title }}</h2>
                <div class="email-template-search-bar btn-group d-flex align-items-center">
                    <label for="" class="me-2">{{ __f("Search Title") }} : </label>
                    <input type="text" id="datatable-search" class="h-100 border w-100 py-2 px-3"
                        placeholder="{{ __f('Name Location Status Placeholder') }}">
                </div>
            </div>
            <table id="categoryData" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __f("SL Title") }}</th>
                        <th>{{ __f("Name Title") }}</th>
                        <th>{{ __f("Review Star Text") }}</th>
                        <th>{{ __f("Review Text") }}</th>
                        <th>{{ __f("Status Title") }}</th>
                        <th>{{ __f("Action Title") }}</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    var tables = $('#categoryData').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        bInfo: true,
        bFilter: false,
        responsive: true,
        ordering: false,
        lengthMenu: [
            [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
            [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
        ],
        pageLength: 25,
        ajax: {
            url: "{{ route('admin.review.get.data') }}",
            type: "POST",
            dataType: "JSON",
            data: function(d) {
                d._token = _token,
                d.search = $('#datatable-search').val();
            },
        },
        columns: [{
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'name'
            },
            {
                data: 'review'
            },
            {
                data: 'review_text'
            },
            {
                data: 'status'
            },
            {
                data: 'action'
            },
        ],
        language: {
            processing: '<img src="{{ asset('backend/assets/img/avatars/table-loading.svg') }}">',
            emptyTable: '<strong class="text-danger">{{ __f("No Data Found Title") }}</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">{{ __f("No Data Found Title") }}</strong>',
            oPaginate: {
                sPrevious: "{{ __f('Paginate Previous Title') }}",
                sNext: "{{ __f('Paginate Next Title') }}",
            },
            lengthMenu: "_MENU_"
        },
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-end' <''B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<''p>>>",
            buttons: [
                {
                    extend: 'pdf',
                    title: 'Admin Project',
                    filename: 'admin_project_{{ date('d-m-Y') }}',
                    text: '<i class="fa-solid fa-file-pdf"></i>  {{ __f("PDF Title") }}',
                    className: 'dataTablesExportBtn mb-3',
                    orientation: "landscape",
                    pageSize: "A4",
                    exportOptions: {
                        columns: '0,1,2'
                    },
                    customize: function(doc) {
                        doc.defaultStyle.alignment = 'center';
                    }
                },
                {
                    extend: 'excel',
                    title: 'Admin Project',
                    filename: 'admin_project_{{ date('d-m-Y') }}',
                    text: '<i class="fa-regular fa-file-excel"></i> {{ __f("Excel Title") }}',
                    className: 'dataTablesExportBtn mb-3',
                    exportOptions: {
                        columns: '0,1,2'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print" aria-hidden="true"></i> {{ __f("Print Title") }}',
                    className: 'dataTablesExportBtn  mb-3',
                    orientation: "landscape",
                    pageSize: "A4",
                    exportOptions: {
                        columns: '0,1,2'
                    }
                }
            ]
    });
    $(document).on('keyup keyup', 'input#datatable-search', function(e) {
        tables.draw();
    });
</script>
@endpush
