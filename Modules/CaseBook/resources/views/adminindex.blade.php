@extends('layouts.app')
@section('title', $title)
@section('add_button')
    <div>
        <a href="{{ route('admin.cashbook-report') }}"
            class="create_btns  btn-md d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-list me-2"></i>
            <span>{{ __f('Report Title') }}</span>
        </a>
    </div>
@endsection
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
                                        <h5 class="card-title">{{ __f('Orginal Assets Title') }}</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber($orginalAssets ?? 0) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Buy Assets Title') }}</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber($buyAssets ?? 0) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Previous Cash Box Amount Title') }}</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber($startDateAmount ?? 0) }}  {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">{{ __f('Today Cash Box Amount Title') }}</h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="stat text-primary">
                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="mt-1 mb-3">{{ convertToLocaleNumber(((int) $startDateAmount + (int) $sellAmount + (int) $collectAmount) - (int) $totalExpences) }} {{ currency() }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="transaction_date" class="form-label fw-semibold">{{ __f('Transaction Date Title') }}</label>
                        <div class="input-group">
                            <input type="date" name="transaction_date" id="transaction_date" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover border">
                    <thead class="table-light">
                        <tr>
                            <th width="30%">{{ __f('Transaction Type Title') }}</th>
                            <th width="40%" class="text-center">{{ __f('Amount Title') }}</th>
                            <th width="30%" class="text-center">{{ __f('Expense Title') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="align-middle">{{ __f('Starting Day Amount Title') }} :</th>
                            <td class="table-light text-center">
                                <span id="startingdayamount">{{ convertToLocaleNumber($startDateAmount ?? 0) }} {{ currency() }}</span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <th class="align-middle">{{ __f('Cash from Sales Title') }} :</th>
                            <td class="table-light text-center">
                                <span id="casefromsales">{{ convertToLocaleNumber($sellAmount ?? 0) }} {{ currency() }} </span>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th class="align-middle">{{ __f('Due Collection Title') }} :</th>
                            <td class="table-light text-center">
                                <span id="cashcollection">{{ convertToLocaleNumber($collectAmount ?? 0) }} {{ currency() }} </span>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th class="align-middle">{{ __f('Expense Amount Title') }} : </th>
                            <td class="table-light">
                            </td>
                            <td class="text-center">
                                <span id="totalexpences">{{ convertToLocaleNumber((int) $totalExpences ?? 0) }} {{ currency() }} </span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1">{{ __f('Total Cash Title') }} : </th>
                            <td class="table-light text-center">
                                <span id="totalcash">{{ convertToLocaleNumber((int) $startDateAmount + (int) $sellAmount + (int) $collectAmount) }} {{ currency() }} </span>
                            </td>
                            <td class="text-center">
                                <span id="totalcashexpences">{{ convertToLocaleNumber((int) $totalExpences ?? 0) }} {{ currency() }} </span>
                            </td>
                        </tr>
                        <tr class="table-light">
                            <th colspan="1">{{ __f('Balance Title') }} :  </th>
                            <td colspan="2" class="text-center">
                                <span id="totalbalance">{{ convertToLocaleNumber(((int) $startDateAmount + (int) $sellAmount + (int) $collectAmount) - (int) $totalExpences) }} {{ currency() }} </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </form>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
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
        $('#transaction_date').on('change', function(e) {
            var data = $(this).val();
            $.ajax({
                url: "{{ route('admin.cashbook.details') }}",
                method: 'POST',
                data: {
                    _token : _token,
                    date : data
                },
                success: function(res) {
                    if (res.status == 'success') {
                        var currency = "{{ currency() }}";
                        $('#startingdayamount').text(`${convertToLocaleNumber(res.data.startDateAmount)} ${currency}`);
                        $('#casefromsales').text(`${convertToLocaleNumber(res.data.sellAmount)} ${currency}`);
                        $('#cashcollection').text(`${convertToLocaleNumber(res.data.collectAmount)} ${currency}`);
                        $('#totalexpences').text(`${convertToLocaleNumber(res.data.totalExpences)} ${currency}`);
                        $('#totalcashexpences').text(`${convertToLocaleNumber(res.data.totalExpences)} ${currency}`);
                        $('#totalcash').text(`${convertToLocaleNumber(res.data.totalCase)} ${currency}`);
                        $('#totalbalance').text(`${convertToLocaleNumber(res.data.totalBalence)} ${currency}`);
                    }
                },
            });
        });
    });
</script>
@endpush
