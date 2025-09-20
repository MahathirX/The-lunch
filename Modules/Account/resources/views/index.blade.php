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
<section>
    <div class="row">
        <div class="col-xl-12 col-xxl-12 d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Orginal Product Buy Price Title') }} </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber($admin_sub_total ?? 0) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Product Buy Price Title') }} </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber($sub_total ?? 0) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Product Sales Amount Title') }} </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber($total_sales_amount ?? 0) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Orginal Product Sales Profit Title') }} </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber((int) $original_sales_price - (int) $total_discount_amount) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Product Sales Profit Title') }} </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber((int) $sales_price - (int) $total_discount_amount) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Dashboard Title') }} </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber($expense ?? 0) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Original Net Profit Title') }} </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{convertToLocaleNumber(( (int) $original_sales_price - (int) $total_discount_amount)  - (int) $expense) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Net Profit Title') }} </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber(( (int) $sales_price - (int) $total_discount_amount)  - (int) $expense) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card px-3 py-3">
                <div class="bg-white border-bottom-0 pb-4 d-flex justify-content-between align-items-center flex-row">
                    <h2 class="backend-title">{{ __f('Profit Fillter Title') }} </h2>
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span><i class="ms-2 fa fa-caret-down"></i>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="expense_list" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ __f('SL Title') }} </th>
                                <th>{{ __f('Date Title') }} </th>
                                <th>{{ __f('Expense Amount Title') }} </th>
                                <th>{{ __f('Discount Amount Title') }}</th>
                                <th>{{ __f('Original Profit Title') }} </th>
                                <th>{{ __f('Profit Title') }} </th>
                                <th>{{ __f('Original Grand Profit Title') }} </th>
                                <th>{{ __f('Grand Profit Title') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/bn.min.js"></script> --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
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
                $.ajax({
                    url: "{{ route('admin.filter.date') }}",
                    type: "GET",
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8'
                    },
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                    },
                    success: function (res) {
                        if(res.status == 'success'){
                            $("#expense_list").empty().append(res.data);
                        }else{
                            $('#expense_list').html('')
                        }
                    },
                });
            }
        });
    </script>
@endpush
