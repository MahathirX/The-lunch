@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-gallary.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/invoice.css') }}">
    <style>
        .invoice-box table tr td:nth-child(n+2) {
            text-align: left !important;
        }

        .suggestions {
            /* display: block !important; */
            width: 600px;
            position: absolute;
            z-index: 999;
        }

        .suggestions ul {
            display: block !important;
        }
    </style>
@endpush
@section('content')
<section>
    <div class="card">
        <div class="card-heading">
            <h3 class="p-2"> {{ __f('Purchase Create Title') }}</h3>
        </div>
        <div class="card-body">
            <form id="PurchaseCreateForm" action="{{ route('staff.purchase.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <x-form.textbox labelName="{{ __f('Choose Date Label Title') }}" parantClass="col-12 col-md-4" name="invoice_date"
                        placeholder="Enter Choose Date" errorName="invoice_date" class="py-2"
                        value="{{ now()->format('Y-m-d') }}" type="date" readonly="readonly"></x-form.textbox>

                    <x-form.selectbox parantClass="col-12 col-md-4 overflow-hidden" class="form-control selectpiker" name="supplier_id" id="supplier_id"
                        required="required" labelName="{{ __f('Search Supplier Name Label Title') }}" errorName="supplier_id">
                        @forelse ($suppliers as $supplier)
                            <option value="{{ $supplier->id ?? '' }}">{{ $supplier->name ?? '' }}</option>
                        @empty
                        <option value="" class="text-danger" disabled>{{ __f('No Supplier Found Text') }}</option>
                        @endforelse
                    </x-form.selectbox>

                    <x-form.selectbox parantClass="col-12 col-md-4" class="form-control py-2" name="status"
                        required="required" labelName="{{ __f('Purchase Status Label Text') }}" errorName="status">
                        <option value="3">{{ __f('Purchase Status Received Title') }}</option>
                    </x-form.selectbox>

                    <x-form.selectbox parantClass="col-12 col-md-3 d-none" class="form-control py-2" name="purchase_type"
                        required="required" labelName="{{ __f('Purchase Type Title') }}" errorName="purchase_type">
                        <option value="0">{{ __f('Purchase Local Title') }}</option>
                    </x-form.selectbox>
                </div>
                <div class="invoice-box mt-3">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __f('Name Title') }}</th>
                                    <th>{{ __f('Quantity Title') }}</th>
                                    <th>{{ __f('Original Price Title') }}</th>
                                    <th>{{ __f('Sub Total Title') }}</th>
                                    <th>{{ __f('Action Title') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item">
                                    <td>
                                        <input type="text" name="productname[]" class="item-field" required autocomplete="off"/>
                                    </td>
                                    <td>
                                        <input name="qty[]" type="number" value="1" class="qty" min="1" required/>
                                    </td>
                                    <td>
                                        <input name="admin_buy_price[]" type="number" class="admin_buy_price" required/>
                                    </td>
                                    <td class="d-none">
                                        <input name="productids[]" type="number" class="productids" />
                                    </td>
                                    <td class="admin_total w-7"> 0.00 {{ currency() }}</td>
                                    <td>
                                        <button type="button" class="btn-delete-row btn btn-sm btn-danger">{{ __f('Delete Title') }}</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tr>
                                <td colspan="4">
                                    <a class="btn-add-row">{{ __f('Add More Item Title') }}</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row position-relative px-2">
                        <div class="col-12 col-md-8" id="invoiceInfoDiv">
                            <div class="suggestions p-1 d-none">
                            </div>
                            <div class="term-condition w-80 mt-4">
                                <x-form.textarea labelName="{{ __f('Note Label Title') }}" parantClass="col-12 col-md-12"
                                    name="note" type="text" placeholder="{{ __f('Note Placeholder') }}"
                                    errorName="note" class="py-2"
                                    ></x-form.textarea>

                                    <li class="text-end" style="list-style: none">
                                        <button type="submit" id="updated_btns" class="btn btn-primary btn-sm mt-3 d-none">
                                            <div class="spinner-border text-light d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>{{ __f('Submit Title') }}
                                        </button>
                                    </li>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" id="invoiceDataDiv">
                            <div class="card p-2 shadow-sm">
                                <div class="card-summary">
                                    <ul class="list-group list-group-flush small">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Original Sub Total Title') }} : </strong>
                                            <span class="admin_sub_total">
                                                0.0 {{ currency() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Discount Title') }} : </strong>
                                            <input type="number" name="discount"
                                                class="form-control form-control-sm discount" value="">
                                        </li>
                                        <!-- Grand Total and Submit Button -->
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Original Grand Total Title') }} :</strong>
                                            <span class="admin_grand_total">
                                                0.0 {{ currency() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Paid amount Title') }} :</strong>
                                            <input type="number" name="paid_amount"
                                                class="form-control form-control-sm paid-amount">
                                        </li>

                                        <div class="input-group mt-2">
                                            <span class="input-group-text">{{ __f('Due amount Title') }}
                                                {{ currency() }} : </span>
                                            <input type="number" name="due_amount"
                                                class="form-control form-control-sm due_amount ">
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <li class="text-end mb-2" style="list-style: none">
                                <button type="submit" id="updated_btn" class="btn btn-primary btn-sm mt-3">
                                    <div class="spinner-border text-light d-none" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>{{ __f('Submit Title') }}
                                </button>
                            </li>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $("#supplier_id").select2({
            tags: true,
            theme: "bootstrap",
            placeholder: "Enter your supplier name",
            allowClear: true
        });
        var currency = "{{ currency() }}";
        $(document).ready(function() {
            const projectRedirectUrl = "{{ route('staff.purchase.index') }}";
            $('#PurchaseCreateForm').on('submit', function(e) {
                e.preventDefault();
                $('.spinner-border').removeClass('d-none');
                $('.error-text').remove();
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status === 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                window.location.href = projectRedirectUrl;
                            }, 10);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $('.spinner-border').addClass('d-none');
                            $.each(errors, function(key, value) {
                                if(key == 'productids.0'){
                                    flashMessage('warning', '{{ __f("Select product & try again Message") }}');
                                }
                                let inputField = $('[name="' + key + '"]');
                                inputField.after(
                                    '<span class="error-text text-danger">' + value[
                                        0] + '</span>'
                                );
                            });
                        } else if (xhr.status === 400) {
                            $('.spinner-border').addClass('d-none')
                            flashMessage(xhr.responseJSON.status, xhr.responseJSON.message);
                        }
                    }
                });
            });
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-add-row').on('click', function() {
                const newRow = `
        <tr class="item">
            <td>
                <input type="text" name="productname[]" class="item-field" required autocomplete="off"/>
            </td>
            <td>
                <input name="qty[]" type="number" value="1" class="qty" min="1" required/>
            </td>
            <td>
                <input name="admin_buy_price[]" type="number" class="admin_buy_price" required/>
            </td>
            <td class="d-none">
                <input name="productids[]" type="number" class="productids"/>
            </td>
            <td class="admin_total w-7"> 0.00 {{ currency() }}</td>
            <td>
                <button type="button" class="btn-delete-row btn btn-sm btn-danger">{{ __f('Delete Title') }}</button>
            </td>
            </tr>`;
                $(this).closest('tr').before(newRow);
            });

            // Current row track
            let rowIndex = '';
            let siblingInput;
            $(document).on('keyup', '.item-field', function() {
                rowIndex = $(this).closest("td");
                siblingInput = rowIndex.siblings().find("input[name='cost[]']");
            });

            // Product search from item field
            $(document).on('keyup', '.item-field', function() {
                let value = $(this).val();
                let suggestions = $('.suggestions');
                let inputField = $(this);

                $.ajax({
                    url: "{{ route('admin.product.purchase.search') }}",
                    method: 'GET',
                    data: {
                        text: value
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            suggestions.html('');
                            suggestions.removeClass('d-none');
                            suggestions.html(res.data);
                            $(".suggestion-item").on("click", function() {
                                var productid = $(this).data('id');
                                rowIndex.find("input").val($(this).text());
                                let siblingInput = rowIndex.closest("tr.item").find(
                                    "input[name='productids[]']");
                                siblingInput.val(productid);
                                suggestions.html('');
                                suggestions.addClass('d-none');
                            });
                        } else {
                            if(res != '' && res != null){
                                flashMessage('error', res.message);
                            }
                            suggestions.html('');
                            suggestions.addClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
                        suggestions.html('');
                        suggestions.addClass('d-none');
                    }
                });
            });



            // Calculate per row total
            let rowCount = 0;

            function totalCal(element) {
                let $row = $(element).closest("tr.item");
                let name = $row.find("input[name='productname[]']").val() || '';
                if (name !== '') {
                    let qty = parseFloat($row.find("input[name='qty[]']").val()) || 0;
                    let adminbuyprice = parseFloat($row.find("input[name='admin_buy_price[]']").val()) || 0;
                    let buyprice = parseFloat($row.find("input[name='buy_price[]']").val()) || 0;
                    if (qty > 0) {
                        let adminTotal = qty * adminbuyprice;
                        let Total = qty * buyprice;
                        $row.find(".admin_total").text(`${currency} ${adminTotal.toFixed(2)}`);
                        $row.find(".total").text(`${currency} ${Total.toFixed(2)}`);
                    }
                } else {
                    $row.find("input[name='qty[]']").val('');
                    $row.find("input[name='productname[]']").val('');
                    $row.find("input[name='admin_buy_price[]']").val('');
                    $row.find("input[name='buy_price[]']").val('');
                    $row.find(".admin_total").text('');
                    $row.find(".total").text('');
                    flashMessage('warning', '{{ __f("Select product first Title") }}');
                }
            }

            $(document).on('click', '.btn-delete-row', function() {
                $(this).closest('tr').remove();
                totalCal(this);
                rowCount = $("tr.item").length;
                calculateSubtotal();
            });

            // Calculate sub total
            function calculateSubtotal() {
                let adminsubtotal = 0;
                let subtotal = 0;
                $("tr.item").each(function() {
                    let admintotalValue = parseFloat($(this).find(".admin_total").text().replace(/[^\d.]/g, '')) || 0;
                    adminsubtotal += admintotalValue;

                    let totalValue = parseFloat($(this).find(".total").text().replace(/[^\d.]/g, '')) || 0;
                    subtotal += totalValue;
                });
                $(".admin_sub_total").text(`${currency}  ${adminsubtotal.toFixed(2)}`);
                $(".admin_grand_total").text(`${currency}  ${adminsubtotal.toFixed(2)}`);
                $(".sub-total").text(`${currency}  ${subtotal.toFixed(2)}`);
                $(".grand-total").text(`${currency}  ${subtotal.toFixed(2)}`);
            }

            // Total count
            $(document).on("change keyup",
                "input[name='qty[]'], input[name='admin_buy_price[]'], input[name='buy_price[]']",
                function() {
                    totalCal(this);
                    rowCount = $("tr.item").length;
                    calculateSubtotal();
                });

            // Total amount dom insert
            function formatAsCurrency(amount) {
                return `${currency} ${Number(amount).toFixed(2)}`;
            }

            // Summary calculate
            $('.card-summary').on("input focus change", ".tax-amount, .paid-amount, .discount, .due_amount",
                function() {
                    let tax = 0;
                    let $card = $(this).closest(".card");
                    let adminsubtotal = parseFloat($card.find(".admin_sub_total").text().replace(/[^\d.]/g, '')) || 0;
                    let subtotal = parseFloat($card.find(".sub-total").text().replace(/[^\d.]/g, '')) || 0;
                    let discount = parseFloat($card.find(".discount").val()) || 0;
                    let paidAmount = parseFloat($card.find(".paid-amount").val()) || 0;
                    let previousDue = parseFloat($card.find(".due_amount").val()) || 0;
                    let grandTotal = (adminsubtotal - tax - discount);
                    let due_amount = grandTotal - paidAmount;

                    $card.find(".admin_grand_total").text(`${currency} ${grandTotal.toFixed(2)}`);
                    $card.find(".due_amount").attr("placeholder", `${due_amount.toFixed(2)}`);

                });
        });
    </script>
@endpush
