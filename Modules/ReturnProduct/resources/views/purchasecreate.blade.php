@extends('layouts.app')
@section('title', $title)
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/image-gallary.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/invoice.css') }}">
<style>
    .suggestions {
        width: 600px;
        position: absolute;
        z-index: 999;
    }
    .suggestions ul {
        display: block;
    }
</style>
@section('content')
<section>
    <div class="card">
        <div class="card-heading">
            <h3 class="p-2">{{ __f('Return Purchase Product Create Title') }}</h3>
        </div>
        <div class="card-body">
            <form id="returnProductFrom" action="{{ route('admin.returnpurchase.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="invoice-box p-4">
                    <div class="row">
                        <div id="text-center" class="col-12 col-md-6">
                            <img src="{{ asset(config('settings.company_secondary_logo')) }}"
                                style="width:100%; max-width:200px;"><br>
                            <span>{{ config('settings.company_name') ?? '' }}</span> <br>
                            <span>{{ config('settings.company_email') ?? '' }}</span>
                            <span id="hr-border"></span>
                        </div>
                        <div id="text-center" class="col-12 col-md-6 text-end">
                            {{ __f('Created Date Title') }}: <input type="date" class="discount text-end" name="create_date"
                                                value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-12 col-md-4 ">
                            <div class=" position-realtive" style="flex: 1;">
                                <input class="form-control search_invoices_number" type="text" name="search_invoices_number"
                                    placeholder="{{ __f('Invoice Number Placeholder') }}" autocomplete="off">
                                <span class="text-danger error-text search_invoices_number-error"></span>
                                <div id="showCustomers"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="" style="flex: 1;">
                                <input class="form-control supplier_name" type="text" name="supplier_name"
                                    placeholder="{{ __f('Supplier Name Placeholder') }}">
                                <span class="text-danger error-text supplier_name-error"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="" style="flex: 1;">
                                <input class="form-control supplier_address" type="text"
                                    name="supplier_address" placeholder="{{ __f('Supplier Address Placeholder') }}">
                                <span class="text-danger error-text supplier_address-error"></span>
                                <input class="form-control" type="hidden" id="customer_id"
                                    name="customer_id">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invoice-box px-2">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0">
                            <tr class="heading">
                                <td>
                                    <h2 class="search-data"></h2>
                                    {{ __f('Name Title') }}
                                </td>
                                <td>{{ __f('Unit Cost Title') }}</td>
                                <td>{{ __f('Quantity Title') }}</td>
                                <td>{{ __f('Total Price Title') }}</td>
                                <td width="20%">{{ __f('Action Title') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <a class="btn-add-row">{{ __f('Add More Item Title') }}</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row position-relative">
                        <div class="col-12 col-md-8" id="invoiceInfoDiv">
                            <div class="suggestions p-1 d-none">
                            </div>
                            <div class="term-condition w-80 mt-4">
                                {{-- <div class="mb-3">
                                    <label class="form-label fw-semibold" for="attachment">Upload
                                        Attachment:</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-sm" type="file" name="attachment"
                                            id="attachment" />
                                        <label class="input-group-text" for="attachment"><i
                                                class="fas fa-upload"></i> Choose File</label>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="fw-semibold">Terms and Conditions:</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms"
                                            id="terms" value="1" checked>
                                        <label for="terms">I agree to the terms and conditions</label>
                                    </div>
                                </div> --}}
                                <div class="mb-2">
                                    <span class="fw-semibold required">{{ __f('Note Title') }}:</span>
                                    <textarea name="note" class="form-control" cols="30" rows="4" placeholder="{{ __f('Note Placeholder') }}"></textarea>
                                </div>
                                {{-- <div class="mt-2">
                                    <x-form.selectbox parantClass="col-12 col-md-12 mb-3" class="form-control py-2"
                                        name="status" labelName="Status" errorName="status">
                                        <option value="1">Complete</option>
                                        <option value="0">Pending</option>
                                    </x-form.selectbox>
                                </div> --}}
                                <li class="text-end" style="list-style: none">
                                    <button type="submit" id="updated_btns" class="btn btn-primary btn-sm mt-3 d-none">
                                        <div class="spinner-border text-light d-none" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>{{ __f('Submit Title') }}
                                    </button>
                                </li>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mt-5" id="invoiceDataDiv">
                            <div class="card p-2 shadow-sm">
                                <div class="card-summary">
                                    <ul class="list-group list-group-flush small">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Refunded Amount Title') }} :</strong>
                                            <span class="sub-total">
                                                0.0 {{ currency() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Total Buy Amount Title') }}:</strong>
                                            <span class="total-paid-amount">
                                                0.0 {{ currency() }}</span>
                                                <input type="hidden" id="total-paid-amount-input">
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Total Due Amount Title') }}:</strong>
                                            <span class="total-due-amount">
                                                0.0 {{ currency() }}</span>
                                                <input type="hidden" id="total-due-amount-input">
                                        </li>
                                        <!-- Grand Total and Submit Button -->
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Grand Refunded Amount Title') }}  :</strong>
                                            <span class="grand-refunded-total">
                                                0.0 {{ currency() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Grand Paid Amount Title') }} :</strong>
                                            <span class="grand-paidable-total">
                                                0.0 {{ currency() }}</span>
                                        </li>
{{--
                                        <div class="input-group mt-2">
                                            <span class="input-group-text">Due amount
                                                {{ currency() }} : </span>
                                            <input type="number" name="due_amount"
                                                class="form-control form-control-sm due_amount ">
                                        </div> --}}
                                    </ul>
                                </div>
                            </div>

                            <li class="text-end mb-3" style="list-style: none">
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
        $(document).ready(function() {
            $('#returnProductFrom').on('submit', function(e) {
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
                                @if(Auth::check() && Auth::user()->role_id == 3)
                                    window.location.href = "/staff/returnpurchase-show/" + res.return_purchase_id;
                                @else
                                    window.location.href = "/admin/returnpurchase-show/" + res.return_purchase_id;
                                @endif
                            }, 10);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $('.spinner-border').addClass('d-none');
                            $.each(errors, function(key, value) {
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

        function fillupcustomerdata(invoiceno, name, address,totalpaid,dueamount,customer_id) {
            var currency = "{{ currency() }}"
            $("input[name='search_invoices_number']").val(invoiceno);
            $("input[name='supplier_name']").val(name);
            $("input[name='supplier_address']").val(address);
            $("#total-paid-amount-input").val(totalpaid);
            $("#total-due-amount-input").val(dueamount);
            $("#customer_id").val(customer_id);
            $(".total-paid-amount").html(totalpaid +' '+ currency);
            $(".total-due-amount").html(dueamount +' '+ currency);
            $('#showCustomers').html('');
        }
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-add-row').on('click', function() {
                const newRow = `
        <tr class="item">
            <td>
                <input type="text" value="" name="productname[]" class="item-field" autocomplete="off"/>
            </td>
            <td>
                <input name="cost[]" readonly type="number" value="" />
            </td>
            <td>
                <input name="qty[]" type="number" value="1" class="qty" min="1" required/>
            </td>
            <td class="d-none">
                <input name="product_qty[]" type="number" class="product_qty"/>
            </td>
            <td class="d-none">
                <input name="product_id[]" type="number" class="product_id"/>
            </td>
            <td class="d-none">
                <input name="batch_no[]" type="number" class="batch_no"/>
            </td>
            <td class="total w-7"> 0.00 {{ currency() }}</td>
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
                let suggestions = $(".suggestions");
                let inputField = $(this);
                var invoiceid = $('.search_invoices_number').val();
                if(invoiceid != null && invoiceid != ''){
                    $.ajax({
                    url: "{{ route('admin.returnpurchase.search') }}",
                    method: 'GET',
                    data: {
                        text: value,
                        invoiceid: invoiceid,
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            suggestions.html(res.data);
                            suggestions.removeClass('d-none');

                            $(".suggestion-item").on("click", function() {

                                let productname = $(this).data("product_name");
                                let productQty = $(this).data("product_qty");
                                let batch_no = $(this).data("batch_no");
                                let productId = $(this).data("id");
                                let productPrice = $(this).data("price");

                                let isDuplicate = false;
                                $("tr.item").each(function() {
                                    const existingName = $(this).find("input[name='productname[]']").val();
                                    const existingId = $(this).find("input[name='product_id[]']").val();

                                    if (existingName === productname || existingId == productId) {
                                        isDuplicate = true;
                                        return false;
                                    }
                                });
                                rowIndex.find("input").val(productname);
                                let siblingInput = rowIndex.closest("tr.item").find("input[name='cost[]']");
                                siblingInput.val(productPrice);
                                let siblingProductId = rowIndex.closest("tr.item").find("input[name='product_id[]']");
                                siblingProductId.val(productId);
                                let siblingBatchNo = rowIndex.closest("tr.item").find("input[name='batch_no[]']");
                                siblingBatchNo.val(batch_no);
                                let siblingProductQty = rowIndex.closest("tr.item").find("input[name='product_qty[]']");
                                siblingProductQty.val(productQty);
                                totalCal(rowIndex.closest("tr.item"));
                                rowCount = $("tr.item").length;
                                calculateSubtotal();
                                suggestions.addClass('d-none');
                            });

                        } else {
                            //inputField.val('');
                            flashMessage(res.status, res.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                    });
                }else{
                    flashMessage('warning', __f("Please provide Message"));
                }

            });

            // Calculate per row total
            let rowCount = 0;

            function totalCal(element) {
                let $row = $(element).closest("tr.item");
                let cost = parseFloat($row.find("input[name='cost[]']").val()) || 0;
                let qty = parseFloat($row.find("input[name='qty[]']").val()) || 0;
                let storeqty = parseFloat($row.find("input[name='product_qty[]']").val()) || 0;
                if(qty > storeqty){
                    $row.find("input[name='qty[]']").val(storeqty);
                    let totalstore = cost * storeqty;
                    $row.find(".total").text(`${totalstore.toFixed(2)} {{ currency() }}`);
                    flashMessage('warning', '{{ __f("Entered quantity exceeds available stock Title") }}');
                }else{
                    let total = cost * qty;
                    $row.find(".total").text(`${total.toFixed(2)} {{ currency() }}`);
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
                let subtotal = 0;
                $("tr.item").each(function() {
                    let totalValue = parseFloat($(this).find(".total").text().replace(/[^\d.]/g, '')) || 0;
                    subtotal += totalValue;
                });
                var dueamount = $('#total-due-amount-input').val();
                var grandrefunded = subtotal - dueamount;
                $(".sub-total").text(`${subtotal.toFixed(2)}  {{ currency() }}`);
                $(".grand-total").text(`${subtotal.toFixed(2)} {{ currency() }}`);
                if(grandrefunded > 0){
                    $(".grand-paidable-total").text(`0.00 {{ currency() }}`);
                    $(".grand-refunded-total").text(`${grandrefunded.toFixed(2)} {{ currency() }}`);
                }else{
                    var grandrepaid = dueamount - subtotal;
                    $(".grand-refunded-total").text(`0.00 {{ currency() }}`);
                    $(".grand-paidable-total").text(`${grandrepaid.toFixed(2)} {{ currency() }}`);
                }
            }

            // Total count
            $(document).on("change keyup", "input[name='qty[]'], input[name='cost[]']", function() {
                totalCal(this);
                rowCount = $("tr.item").length;
                calculateSubtotal();
            });

            // Total amount dom insert
            function formatAsCurrency(amount) {
                return `${Number(amount).toFixed(2)} {{ currency() }}`;
            }

            // Summary calculate
            $('.card-summary').on("input focus change", ".tax-amount, .paid-amount, .discount, .due_amount",
                function() {
                    let $card = $(this).closest(".card");
                    let subtotal = parseFloat($card.find(".sub-total").text().replace(/[^\d.]/g, '')) || 0;
                    let tax = 0;
                    let discount = parseFloat($card.find(".discount").val()) || 0;
                    let paidAmount = parseFloat($card.find(".paid-amount").val()) || 0;
                    let previousDue = parseFloat($card.find(".due_amount").val()) || 0;
                    let grandTotal = (subtotal - tax - discount);
                    let due_amount = grandTotal - paidAmount;

                    $card.find(".grand-total").text(
                        `${grandTotal.toFixed(2)} {{ currency() }}`);
                    $card.find(".due_amount").attr("placeholder", `${due_amount.toFixed(2)}`);

                });

            $('.search_invoices_number').on('keyup', function() {
                let invoiceno = $(this).val().trim();
                $.ajax({
                    url: "{{ route('admin.returnpurchase.invoice.search') }}",
                    data: {
                        invoiceno: invoiceno,
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('#showCustomers').html('');
                            $('#showCustomers').html(res.invoices);
                        } else {
                            $('#showCustomers').html('');
                            $('#showCustomers').html(res.invoices);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });

            });
        });
    </script>
@endpush
