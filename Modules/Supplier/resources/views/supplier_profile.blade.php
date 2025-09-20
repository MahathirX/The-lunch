@extends('layouts.app')
@section('title', $title)
@push('styles')
<style>
    #invoiceDetailsTables thead tr{
	  border-bottom: 2px solid #b5b4b4;
    }
    #invoiceDetailsTables tbody tr{
	  border-bottom: 1px solid #b5b4b4;
    }
    #invoiceDetailsTables tbody tr:last-child{
	  border-bottom: none !important;
    }
</style>
@endpush
@section('content')
    <div class="row mt-3">
        <div class="col-md-12">
            <!-- Data Table -->
            <div class="card px-3 py-3">
                <div class="bg-white border-bottom-0 pb-4 d-flex justify-content-between align-items-center flex-row">
                    <h2 class="backend-title">{{ $title }}</h2>
                </div>
                <div class="card shadow-sm border-0 p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <img src="{{ $supplier->photo ? asset($supplier->photo) : asset('backend/assets/images/pngtree-default.png') }}"
                                    class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;"
                                    alt="{{ $supplier->name }}">
                                <div class="ms-3">
                                    <h5 class="fw-bold mb-1">{{ $title }}</h5>
                                    <p class="mb-0 text-muted"><i class="fas fa-user me-2"></i>{{ $supplier->name ?? '' }}
                                    </p>
                                    <p class="mb-0 text-muted"><i class="fas fa-phone me-2"></i>{{ $supplier->phone ?? '' }}
                                    </p>
                                    <p class="mb-0 text-muted"><i
                                            class="fas fa-map-marker-alt me-2"></i>{{ $supplier->address ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="ms-3">
                                    <h5 class="fw-bold mb-1">{{ __f('Total Due Title') }}</h5>
                                    <p class="mb-0 text-muted"><i class="fas fa-wallet me-2"></i><span
                                            id="previous_dues">{{ convertToLocaleNumber((int) $supplier->previous_due + (int) $orginal_due_amount) }}</span> {{ currency() }}</p>
                                    <button type="button" class="btn btn-primary mt-2" id="paidAmountBtn"
                                        data-id="{{ $supplier->id }}">
                                        {{ __f('Pay Due Buttn Title') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-bottom-0 pb-4">
                    <div class="card border-0">
                        <div class="row">
                            <!-- Row 1 -->
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Orginal Purchase Amount Title') }}</h6>
                                    <p class="text-muted mb-0"><i class="fas fa-wallet me-2"></i>
                                        {{ convertToLocaleNumber((int) $orginal_sub_total ?? 0) }} {{ currency() }}</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Purchase Amount Title') }}</h6>
                                    <p class="text-muted mb-0"><i class="fas fa-wallet me-2"></i>
                                        {{ convertToLocaleNumber((int) $sub_total ?? 0) }} {{ currency() }}</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Discount Title') }}</h6>
                                    <p class="text-muted mb-0"><i class="fas fa-wallet me-2"></i>
                                        {{ convertToLocaleNumber((int) $discount ?? 0) }} {{ currency() }}</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Total Paid Amount Title') }}</h6>
                                    <p class="text-muted mb-0"><i class="fas fa-wallet me-2"></i>
                                        <span id="total_paid_amount">{{ convertToLocaleNumber((int) $paid_amount ?? 0) }} {{ currency() }}</span></p>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Orginal Due amount Title') }}</h6>
                                    <p class="text-muted mb-0"><i class="fas fa-wallet me-2"></i>
                                       <span id="original_due">{{ convertToLocaleNumber((int) $orginal_due_amount ?? 0) }} {{ currency() }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Previous Due amount Title') }}</h6>
                                    <p class="text-muted mb-0"><i class="fas fa-wallet me-2"></i>
                                       <span id="previous_due_amount">{{ convertToLocaleNumber((int) $supplier->previous_due ?? 0) }} {{ currency() }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Total Product Quantity Title') }} </h6>
                                    <p class="text-muted mb-0">{{ convertToLocaleNumber((int) $total_qty ?? 0) }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Total Invoice Title') }}</h6>
                                    <p class="text-muted mb-0">{{ convertToLocaleNumber((int) $invoices ?? 0) }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold">{{ __f('Account Status Title') }}</h6>
                                    <p class="text-muted mb-0">
                                        <span class="text-success fw-bold">
                                            {!! $supplier->status == 1
                                                ? '<span class="text-success"><i class="fas fa-check-circle"></i> ' . __f("Status Publish Title") . '</span>'
                                                : '<span class="text-warning"><i class="fa-solid fa-hourglass-end"></i> ' . __f("Status Pending Title") . '</span>' !!}                                            
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card px-3 py-3">
                            <div
                                class="bg-white border-bottom-0 pb-4 d-flex justify-content-between align-items-center flex-row">
                                <h2 class="backend-title">{{ __f('Due Payment History Title') }}</h2>
                                <div class="email-template-search-bar btn-group d-flex align-items-center">
                                    <label for="" class="me-2">{{ __f("Search Title") }} : </label>
                                    <select name="datatable-search" id="datatable-search" class="form-control">
                                        <option value="">{{ __f('Select Date Title') }}</option>
                                        <option value="">{{ __f('Reset Fillter Title') }}</option>
                                        @foreach ($paiddates as $key => $date)
                                            <option value="{{ $date ?? '' }}">
                                                {{ formatDateByLocale(\Carbon\Carbon::parse($date)->format('d-m-Y'))?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <table id="due_collection_table" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>{{ __f('SL Title') }}</th>
                                        <th>{{ __f('Paid Date Title') }}</th>
                                        <th>{{ __f('Name Title') }}</th>
                                        <th>{{ __f('Phone Title') }}</th>
                                        <th>{{ __f('Previous Due Title') }}</th>
                                        <th>{{ __f('Paid Amount Title') }}</th>
                                        <th>{{ __f('Current Amount Title') }}</th>
                                        <th>{{ __f('Payment By Title') }}</th>
                                        <th>{{ __f('PDF Title') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if(Auth::check() && Auth::user()->role_id != 3)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card px-3 py-3">
                                <div
                                    class="bg-white border-bottom-0 pb-4 d-flex justify-content-between align-items-center flex-row">
                                    <h2 class="backend-title">{{ __f('Purchase Details Title') }}</h2>
                                    <div class="email-template-search-bar btn-group d-flex align-items-center">
                                        <label for="" class="me-2">{{ __f('Search Title') }} : </label>
                                        <input type="text" id="datatable-search-profile" class="h-100 border w-100 py-2 px-3" placeholder="{{ __f('Sales Search Placeholder') }}">
                                    </div>
                                </div>
                                <table id="purchase_details_tables" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __f('SL Title') }}</th>
                                            <th>{{ __f('Invoice Id Title') }}</th>
                                            <th>{{ __f('Invoice Date Title') }}</th>
                                            <th>{{ __f('Supplier Name Title') }}</th>
                                            <th>{{ __f('Total Quantity Title') }}</th>
                                            <th>{{ __f('Total Orginal Amount Title') }}</th>
                                            <th>{{ __f('Total Amount Title') }}</th>
                                            <th>{{ __f('Discount Title') }}</th>
                                            <th>{{ __f('Paid Amount Title') }}</th>
                                            <th>{{ __f('Due Amount Title') }}</th>
                                            <th>{{ __f('Purchase By Title') }}</th>
                                            <th>{{ __f('Purchase Type Title') }}</th>
                                            <th>{{ __f('Note Title') }}</th>
                                            <th>{{ __f('Status Title') }}</th>
                                            <th>{{ __f('Action Title') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('components.models.paidsupplieramount')
@endsection
@push('scripts')
<script>
    const currentLang = "{{ app()->getLocale() }}" || 'en'; 
    var supplierid = "{{ $supplierid }}";
    var currency = "{{ currency() }}";

    var tables = $('#due_collection_table').DataTable({
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
            url: "{{ route('admin.supplier.user.due.get.data') }}",
            type: "GET",
            dataType: "JSON",
            data: function(d) {
                d._token = _token,
                    d.date = $('#datatable-search').val();
                    d.supplierid = supplierid;
            },
        },
        columns: [{
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                if(currentLang == 'bn'){
                    return toBanglaNumber(data);
                }
                    return data;
                }
            },
            {
                data: 'paid_date'
            },
            {
                data: 'name'
            },
            {
                data: 'phone'
            },
            {
                data: 'previous_due'
            },
            {
                data: 'paid_amount'
            },
            {
                data: 'present_amount'
            },
            {
                data: 'payment_by'
            },
            {
                data: 'pdf'
            },
        ],
        language: {
            processing: '<img src="{{ asset('backend/assets/img/avatars/table-loading.svg') }}">',
            emptyTable: '<strong class="text-danger">{{ __f("No Data Found Title") }}</strong>',
            infoEmpty: '',
            info: "{{ __f('DataTables Showing Title') }}",
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
        buttons: [{
                extend: 'pdf',
                title: 'Admin Product',
                filename: 'admin_product_{{ date('d-m-Y') }}',
                text: '<i class="fa-solid fa-file-pdf"></i> {{ __f("PDF Title") }}',
                className: 'dataTablesExportBtn mb-3',
                orientation: "landscape",
                pageSize: "A4",
                exportOptions: {
                    columns: '0,1,2,3,4,5,6'
                },
                customize: function(doc) {
                    doc.defaultStyle.alignment = 'center';
                }
            },
            {
                extend: 'excel',
                title: 'Admin Product',
                filename: 'admin_product_{{ date('d-m-Y') }}',
                text: '<i class="fa-regular fa-file-excel"></i>  {{ __f("Excel Title") }}',
                className: 'dataTablesExportBtn mb-3',
                exportOptions: {
                    columns: '0,1,2,3,4,5,6'
                }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print" aria-hidden="true"></i> {{ __f("Print Title") }}',
                className: 'dataTablesExportBtn  mb-3',
                orientation: "landscape",
                pageSize: "A4",
                exportOptions: {
                    columns: '0,1,2,3,4,5,6'
                }
            }
        ]
    });
    $(document).on('change', '#datatable-search', function(e) {
        tables.draw();
    });

    var salesTables = $('#purchase_details_tables').DataTable({
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
            url: "{{ route('admin.supplier.purchase.get.data') }}",
            type: "GET",
            dataType: "JSON",
            data: function(d) {
                d._token = _token,
                    d.search = $('#datatable-search-profile').val();
                    d.supplierid = supplierid;
            },
        },
        columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                    if(currentLang == 'bn'){
                        return toBanglaNumber(data);
                    }
                        return data;
                    }
                },
                {
                    data: 'invoice_id'
                },
                {
                    data: 'invoice_date'
                },
                {
                    data: 'supplier_name'
                },
                {
                    data: 'total_quantity'
                },
                {
                    data: 'total_admin_amount'
                },
                {
                    data: 'total_amount'
                },
                {
                    data: 'discount'
                },
                {
                    data: 'paid_amount'
                },
                {
                    data: 'due_amount'
                },
                {
                    data: 'purchase_by'
                },
                {
                    data: 'purchase_type'
                },
                {
                    data: 'note'
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
                info: "{{ __f('DataTables Showing Title') }}",
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
        buttons: [{
                extend: 'pdf',
                title: 'Admin Product',
                filename: 'admin_product_{{ date('d-m-Y') }}',
                text: '<i class="fa-solid fa-file-pdf"></i> {{ __f("PDF Title") }}',
                className: 'dataTablesExportBtn mb-3',
                orientation: "landscape",
                pageSize: "A4",
                exportOptions: {
                    columns: '0,1,2,3,4,5,6,7,8'
                },
                customize: function(doc) {
                    doc.defaultStyle.alignment = 'center';
                }
            },
            {
                extend: 'excel',
                title: 'Admin Product',
                filename: 'admin_product_{{ date('d-m-Y') }}',
                text: '<i class="fa-regular fa-file-excel"></i>  {{ __f("Excel Title") }}',
                className: 'dataTablesExportBtn mb-3',
                exportOptions: {
                    columns: '0,1,2,3,4,5,6,7,8'
                }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print" aria-hidden="true"></i> {{ __f("Print Title") }}',
                className: 'dataTablesExportBtn  mb-3',
                orientation: "landscape",
                pageSize: "A4",
                exportOptions: {
                    columns: '0,1,2,3,4,5,6,7,8'
                }
            }
        ]
    });
    $(document).on('keyup', '#datatable-search-profile', function(e) {
        salesTables.draw();
    });

    function convertToLocaleNumber(number) {
        const currentLang = "{{ app()->getLocale() }}" || 'en'; 
        const map = {
            bn: ['০','১','২','৩','৪','৫','৬','৭','৮','৯'],
            en: ['0','1','2','3','4','5','6','7','8','9'],
        };

        const targetDigits = map[currentLang] || map['en'];
        return number.toString().split('').map(char => {
            if (!isNaN(char) && map['en'].includes(char)) {
                return targetDigits[parseInt(char)];
            }
            return char;
        }).join('');
    }

    $(document).ready(function() {

        var PaidSupplierAmountModalLabels = new bootstrap.Modal(document.getElementById('PaidSupplierAmountModal'), {
            keyboard: false,
            backdrop: false,
        })

        $(document).on('click', '#paidAmountBtn', function() {
            var userid = $(this).data('id');
            $('#paidamountuserid').val(userid);
            PaidSupplierAmountModalLabels.show();
        });

        $('#paidsupplieramountsubmit').on('submit', function(e) {
            e.preventDefault();
            $('.spinner-border-paid').removeClass('d-none');
            $('.error-text').text('');
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#total_paid_amount').html(convertToLocaleNumber(res.paidamount) + ' ' + currency)
                    $('#original_due').html(convertToLocaleNumber(res.dueamount) + ' ' + currency)
                    $('#previous_due_amount').html(convertToLocaleNumber(res.previsudueamount) + ' ' + currency)
                    if (res.status === "success") {
                        PaidSupplierAmountModalLabels.hide();
                        if (res.pdf_url) {
                            $('#previous_dues').html(convertToLocaleNumber(res.previous_due))
                            $('#paidamount').val('');
                            $('.spinner-border-paid').addClass('d-none');
                            tables.draw();
                            salesTables.draw();
                            window.open(res.pdf_url, '_blank');
                        } else {
                            $('#previous_dues').html(convertToLocaleNumber(res.previous_due))
                            $('#paidamount').val('');
                            $('.spinner-border-paid').addClass('d-none');
                            tables.draw();
                            salesTables.draw();
                            flashMessage(res.status, res.message);
                        }
                    } else {
                        $('#paidamount').val('');
                        $('.spinner-border-paid').addClass('d-none');
                        flashMessage(res.status, res.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $('.spinner-border').addClass('d-none');
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('.' + key + '-error').text(value[0]);
                        });
                    } else {
                        $('.spinner-border').addClass('d-none');
                        console.log('Something went wrong. Please try again.');
                    }
                }
            });
        });
    });
</script>
@endpush
