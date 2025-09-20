@extends('layouts.app')
@section('title', $title)
@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .tfoot{
        border-top: 2px solid #ddd;
    }
</style>
@endpush
@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card px-3 py-3">
            <div class="bg-white border-bottom-0 pb-4 d-flex justify-content-between align-items-center flex-row">
                <h2 class="backend-title">{{ $title }}</h2>
                <div class="email-template-search-bar btn-group d-flex align-items-center justify-content-end">
                    <label for="" class="me-2">{{ __f("Search Title") }} : </label>
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span><i class="ms-2 fa fa-caret-down"></i>
                    </div>
                </div>
            </div>
            <table id="cashBooks" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __f('SL Title') }}</th>
                        <th>{{ __f('Date Title') }}</th>
                        <th>{{ __f('Total Cash Title') }}</th>
                        <th>{{ __f('Expense Amount Title') }}</th>
                        <th>{{ __f('Local Purchas Amount Title') }}</th>
                        <th>{{ __f('Balance Title') }} </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align:right">{{ __f('Total Title') }}:</th>
                        <th id="footer_total_cash"></th>
                        <th id="footer_expense_amount"></th>
                        <th id="footer_local_amount"></th>
                        <th id="footer_balance"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(function () {

        const datePickerLocales = {
                en: {
                    format: 'DD-MM-YYYY',
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 0
                },
                bn: {
                    format: 'DD-MM-YYYY',
                    applyLabel: 'প্রয়োগ করুন',
                    cancelLabel: 'বাতিল করুন',
                    daysOfWeek: ['রবি','সোম','মঙ্গল','বুধ','বৃহঃ','শুক্র','শনি'],
                    monthNames: ['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'],
                    firstDay: 0
                }
            };

            const rangeLabels = {
                en: {
                    'Today': 'Today',
                    'Yesterday': 'Yesterday',
                    'Last 7 Days': 'Last 7 Days',
                    'Last 30 Days': 'Last 30 Days',
                    'This Month': 'This Month',
                    'Last Month': 'Last Month',
                    'Custom Range' : 'Custom Range',
                },
                bn: {
                    'Today': 'আজ',
                    'Yesterday': 'গতকাল',
                    'Last 7 Days': 'গত ৭ দিন',
                    'Last 30 Days': 'গত ৩০ দিন',
                    'This Month': 'এই মাস',
                    'Last Month': 'গত মাস',
                    'Custom Range' : 'মাস',
                }
            };

            const currentLang = "{{ app()->getLocale() }}" || 'en';
            moment.locale(currentLang);

            function convertToBanglaNumber(str) {
                const en = ['0','1','2','3','4','5','6','7','8','9'];
                const bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                return str.split('').map(char => {
                    const index = en.indexOf(char);
                    return index > -1 ? bn[index] : char;
                }).join('');
            }

            function formatDateLocalized(date) {
                const formatted = date.format("MMMM D, YYYY"); 
                if (currentLang === 'bn') {
                    return convertToBanglaNumber(formatted);
                }
                return formatted;
            }

            const ranges = {};
            Object.keys(rangeLabels[currentLang]).forEach((key) => {
                const label = rangeLabels[currentLang][key];
                switch (key) {
                    case 'Today':
                        ranges[label] = [moment(), moment()];
                        break;
                    case 'Yesterday':
                        ranges[label] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
                        break;
                    case 'Last 7 Days':
                        ranges[label] = [moment().subtract(6, "days"), moment()];
                        break;
                    case 'Last 30 Days':
                        ranges[label] = [moment().subtract(29, "days"), moment()];
                        break;
                    case 'This Month':
                        ranges[label] = [moment().startOf("month"), moment().endOf("month")];
                        break;
                    case 'Last Month':
                        ranges[label] = [
                            moment().subtract(1, "month").startOf("month"),
                            moment().subtract(1, "month").endOf("month")
                        ];
                        break;
                }
            });



        let start = moment().subtract(29, "days");
        let end = moment();
        let table;
        let currency = "{{ currency() }}";


        function cb(start, end) {
            $("#reportrange span").html(
                formatDateLocalized(start) + " - " + formatDateLocalized(end)
            );
            fetchData(start.format("YYYY-MM-DD"), end.format("YYYY-MM-DD"));
        }

        $("#reportrange").daterangepicker(
            {
                startDate: start,
                endDate: end,
                locale: datePickerLocales[currentLang],
                ranges: ranges
            },
            cb
        );
        cb(start, end); 
        function fetchData(startDate, endDate) {
            if ($.fn.dataTable.isDataTable('#cashBooks')) {
                $('#cashBooks').DataTable().clear().destroy();
            }
            table = $('#cashBooks').DataTable({
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
                    url: "{{ route('staff.cashbook.report.get.data') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: function(d) {
                        d._token = _token;
                        d.search = $('#datatable-search').val();
                        d.start_date = startDate;
                        d.end_date = endDate;
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
                        data: 'date'
                    },
                    {
                        data: 'total_cash',
                        render: function (data, type, row, meta) {
                        if(currentLang == 'bn'){
                            return toBanglaNumber(data);
                        }
                            return data;
                        }
                    },
                    {
                        data: 'expense_amount',
                        render: function (data, type, row, meta) {
                        if(currentLang == 'bn'){
                            return toBanglaNumber(data);
                        }
                            return data;
                        }
                    },
                    {
                        data: 'local_purchas_amount',
                        render: function (data, type, row, meta) {
                        if(currentLang == 'bn'){
                            return toBanglaNumber(data);
                        }
                            return data;
                        }
                    },
                    {
                        data: 'balance',
                        render: function (data, type, row, meta) {
                        if(currentLang == 'bn'){
                            return toBanglaNumber(data);
                        }
                            return data;
                        }
                    },
                ],
                footerCallback: function (row, data, start, end, display) {
                    let totalCash = 0;
                    let totalExpense = 0;
                    let totalLocalPurchas = 0;
                    let totalBalance = 0;

                    data.forEach(function (row) {
                        let cash = parseFloat(row.sell_amount || 0) + parseFloat(row.collect_amount || 0);
                        let expense = parseFloat(row.expense_amount || 0);
                        let localpurchas = parseFloat(row.localPurchaseAmount || 0);
                        totalCash += cash;
                        totalExpense += expense;
                        totalLocalPurchas += localpurchas;
                        totalBalance += (cash - (expense + localpurchas));
                        console.log(row);
                    });
                    $('#footer_total_cash').html(toBanglaNumber(totalCash.toLocaleString()) + ' ' + currency);
                    $('#footer_expense_amount').html(toBanglaNumber(totalExpense.toLocaleString()) + ' ' + currency);
                    $('#footer_local_amount').html(toBanglaNumber(totalLocalPurchas.toLocaleString()) + ' ' + currency);
                    $('#footer_balance').html(toBanglaNumber(totalBalance.toLocaleString()) + ' ' + currency);
                },
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
                buttons: [
                    {
                        extend: 'pdf',
                        title: 'Admin Product',
                        filename: 'admin_product_{{ date('d-m-Y') }}',
                        text: '<i class="fa-solid fa-file-pdf"></i> {{ __f("PDF Title") }}',
                        className: 'dataTablesExportBtn mb-3',
                        orientation: "landscape",
                        pageSize: "A4",
                        exportOptions: {
                            columns: '0,1,2,3,4,5'
                        },
                        customize: function(doc) {
                            doc.defaultStyle.alignment = 'center';
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'Admin Product',
                        filename: 'admin_product_{{ date('d-m-Y') }}',
                        text: '<i class="fa-regular fa-file-excel"></i> {{ __f("Excel Title") }}',
                        className: 'dataTablesExportBtn mb-3',
                        exportOptions: {
                            columns: '0,1,2,3,4,5'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print" aria-hidden="true"></i> {{ __f("Print Title") }}',
                        className: 'dataTablesExportBtn  mb-3',
                        orientation: "landscape",
                        pageSize: "A4",
                        exportOptions: {
                            columns: '0,1,2,3,4,5'
                        }
                    }
                ]
            });

            $(document).on('keyup', 'input#datatable-search', function(e) {
                table.draw();
            });
        }
    });
</script>
@endpush
