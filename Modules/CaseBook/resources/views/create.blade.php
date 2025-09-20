@extends('layouts.app')
@section('title', $title)
@section('add_button')
    <div>
        <a href="{{ route('staff.cashbook-report') }}"
            class="create_btns  btn-md d-flex justify-content-between align-items-center">
            <i class="fa-solid fa-list me-2"></i>
            <span>{{ __f('Report Title') }}</span>
        </a>
    </div>
@endsection
@section('content')
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
                                <span id="startingdayamount">{{ $startDateAmount ?? 0 }} {{ currency() }}</span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <th class="align-middle">{{ __f('Cash from Sales Title') }} :</th>
                            <td class="table-light text-center">
                                <span id="casefromsales">{{ $sellAmount ?? 0 }} {{ currency() }} </span>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th class="align-middle">{{ __f('Due Collection Title') }} :</th>
                            <td class="table-light text-center">
                                <span id="cashcollection">{{ $collectAmount ?? 0 }} {{ currency() }} </span>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th class="align-middle">{{ __f('Expense Amount Title') }} : </th>
                            <td class="table-light">
                            </td>
                            <td class="text-center">
                                <span id="totalexpences">{{ (int) $totalExpences ?? 0 }} {{ currency() }} </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="align-middle">{{ __f('Purchase From Local Market') }} : </th>
                            <td class="table-light">
                            </td>
                            <td class="text-center">
                                <span id="localpurchaseamount">{{ (int) $localPurchase ?? 0 }} {{ currency() }} </span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1">{{ __f('Total Cash Title') }} : </th>
                            <td class="table-light text-center">
                                <span id="totalcash">{{ (int) $startDateAmount + (int) $sellAmount + (int) $collectAmount }} {{ currency() }} </span>
                            </td>
                            <td class="text-center">
                                <span id="totalcashexpences">{{ (int) $totalExpences + (int) $localPurchase  }} {{ currency() }} </span>
                            </td>
                        </tr>
                        <tr class="table-light">
                            <th colspan="1">{{ __f('Balance Title') }} :  </th>
                            <td colspan="2" class="text-center">
                                <span id="totalbalance">{{ ((int) $startDateAmount + (int) $sellAmount + (int) $collectAmount) - ((int) $totalExpences + (int) $localPurchase ) }} {{ currency() }} </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#transaction_date').on('change', function(e) {
                var data = $(this).val();
                $.ajax({
                    url: "{{ route('staff.cashbook.details') }}",
                    method: 'POST',
                    data: {
                        _token : _token,
                        date : data
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            var currency = "{{ currency() }}";
                            $('#startingdayamount').text(`${res.data.startDateAmount} ${currency}`);
                            $('#casefromsales').text(`${res.data.sellAmount} ${currency}`);
                            $('#cashcollection').text(`${res.data.collectAmount} ${currency}`);
                            $('#totalexpences').text(`${res.data.totalExpences} ${currency}`);
                            $('#totalcashexpences').text(`${res.data.totalExpences} ${currency}`);
                            $('#localpurchaseamount').text(`${res.data.localPurchase} ${currency}`);
                            $('#totalcash').text(`${res.data.totalCase} ${currency}`);
                            $('#totalbalance').text(`${res.data.totalBalence} ${currency}`);
                        }
                    },
                });
            });
        });
    </script>
@endpush
