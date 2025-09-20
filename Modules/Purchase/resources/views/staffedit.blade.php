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
            <h3 class="p-2"> Purchase Product Edit Form</h3>
        </div>
        <div class="card-body">
            <form id="PurchaseEditForm" action="{{ route('staff.purchase.updates',['id' => $editinvoice->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <x-form.textbox labelName="Choose Date" parantClass="col-12 col-md-4" name="invoice_date"
                        placeholder="Enter Choose Date" errorName="invoice_date" class="py-2"
                        value="{{ $editinvoice->invoice_date ??  old('invoice_date') }}" type="date" required="required"></x-form.textbox>

                    <x-form.selectbox parantClass="col-12 col-md-4 overflow-hidden" class="form-control selectpiker" name="supplier_id" id="supplier_id"
                        required="required" labelName="Search Supplier Name" errorName="supplier_id">
                        @forelse ($suppliers as $supplier)
                            <option value="{{ $supplier->id ?? '' }}" {{ $editinvoice->supplier_id == $supplier->id ? 'selected' : ''}}>{{ $supplier->name ?? '' }}</option>
                        @empty
                        <option value="" class="text-danger" disabled>No Supplier Found</option>
                        @endforelse
                    </x-form.selectbox>

                    <x-form.selectbox parantClass="col-12 col-md-4" class="form-control py-2" name="status"
                        required="required" labelName="Purchase Status" errorName="status">
                        <option value="3" {{ $editinvoice->status == '3' ? 'selected' : '' }}>Received</option>
                    </x-form.selectbox>
                    <x-form.selectbox parantClass="col-12 col-md-3 d-none" class="form-control py-2" name="purchase_type"
                        required="required" labelName="Purchase Type" errorName="purchase_type">
                        <option value="0" {{ $editinvoice->purchase_type == '0' ? 'selected' : '' }}>Local</option>
                    </x-form.selectbox>
                </div>
                <div class="invoice-box mt-3">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Original Buy Price</th>
                                    <th>Original Sub Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($editinvoice->purchaseinvoicedetails as $invoice)
                                <tr class="item">
                                    <td>
                                        <input type="text" name="productname[]" class="item-field" required value="{{ $invoice->product->name ?? '' }}" autocomplete="off"/>
                                    </td>
                                    <td>
                                        <input name="qty[]" type="number" class="qty" min="1" required value="{{ $invoice->qty ?? 0 }}"/>
                                    </td>
                                    <td>
                                        <input name="admin_buy_price[]" type="number" class="admin_buy_price" required value="{{ $invoice->admin_buy_price ?? 0 }}"/>
                                    </td>
                                    <td class="d-none">
                                        <input name="productids[]" type="number" class="productids" value="{{ $invoice->product->id ?? '' }}"/>
                                    </td>
                                    <td class="admin_total w-7"> {{ $invoice->admin_sub_total ?? 0 }} {{ currency() }}</td>
                                    <td>
                                        <button type="button" class="btn-delete-row btn btn-sm btn-danger">Delete</button>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-danger">No Product Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tr>
                                <td colspan="4">
                                    <a class="btn-add-row">Add More Item</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row position-relative px-2">
                        <div class="col-12 col-md-8" id="invoiceInfoDiv">
                            <div class="suggestions p-1 d-none">
                            </div>
                            <div class="term-condition w-80 mt-4">
                                <x-form.textarea labelName="Note" parantClass="col-12 col-md-12"
                                        name="note" type="text" placeholder="Note"
                                        errorName="note" class="py-2" value="{{ $editinvoice->note ?? '' }}"
                                        ></x-form.textarea>

                                    <li class="text-end" style="list-style: none">
                                        <button type="submit" id="updated_btns" class="btn btn-primary btn-sm mt-3 d-none">
                                            <div class="spinner-border text-light d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>Submit
                                        </button>
                                    </li>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" id="invoiceDataDiv">
                            <div class="card p-2 shadow-sm">
                                <div class="card-summary">
                                    <ul class="list-group list-group-flush small">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Previous Paid Amount : </strong>
                                            <span class="previou-paid-amount">
                                                {{ $editinvoice->paid_amount ?? 0 }} {{ currency() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Previous Due Amount : </strong>
                                            @if ($editinvoice->paid_amount != null && $editinvoice->discount != null)
                                              <span class="previou-due-amount"> {{ ($editinvoice->admin_sub_total - $editinvoice->discount) -  $editinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif ($editinvoice->paid_amount != null)
                                              <span class="previou-due-amount"> {{ $editinvoice->admin_sub_total - $editinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif($editinvoice->discount != null)
                                              <span class="previou-due-amount"> {{ $editinvoice->admin_sub_total - $editinvoice->discount }} {{ currency() }}</span>
                                            @else
                                              <span class="previou-due-amount"> {{ $editinvoice->admin_sub_total }} {{ currency() }}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Previous Discount Amount : </strong>
                                            <span class="previou-discount-total"> {{ $editinvoice->discount ?? 0 }} {{ currency() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Original Sub Total : </strong>
                                            @if ($editinvoice->paid_amount != null && $editinvoice->discount != null)
                                              <span class="previou-admin-sub-total"> {{ ($editinvoice->admin_sub_total - $editinvoice->discount) -  $editinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif ($editinvoice->paid_amount != null)
                                              <span class="previou-admin-sub-total"> {{ $editinvoice->admin_sub_total - $editinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif($editinvoice->discount != null)
                                              <span class="previou-admin-sub-total"> {{ $editinvoice->admin_sub_total - $editinvoice->discount }} {{ currency() }}</span>
                                            @else
                                              <span class="previou-admin-sub-total"> {{ $editinvoice->admin_sub_total }} {{ currency() }}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Discount : </strong>
                                            <input type="number" name="discount"
                                                class="form-control form-control-sm discount" value="{{ $editinvoice->discount }}">
                                        </li>
                                        <!-- Grand Total and Submit Button -->
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Original Grand Total :</strong>
                                            @if ($editinvoice->paid_amount != null && $editinvoice->discount != null)
                                              <span class="admin_sub_total"> {{ ($editinvoice->admin_sub_total - $editinvoice->discount) -  $editinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif ($editinvoice->paid_amount != null)
                                              <span class="admin_sub_total"> {{ $editinvoice->admin_sub_total - $editinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif($editinvoice->discount != null)
                                              <span class="admin_sub_total"> {{ $editinvoice->admin_sub_total - $editinvoice->discount }} {{ currency() }}</span>
                                            @else
                                              <span class="admin_sub_total"> {{ $editinvoice->admin_sub_total }} {{ currency() }}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>Paid amount :</strong>
                                            <input type="number" name="paid_amount"
                                                class="form-control form-control-sm paid-amount">
                                        </li>

                                        <div class="input-group mt-2">
                                            <span class="input-group-text">Due amount
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
                                    </div>Submit
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
            $('#PurchaseEditForm').on('submit', function(e) {
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
                                    flashMessage('warning', 'Select product & try again');
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
                <button type="button" class="btn-delete-row btn btn-sm btn-danger">Delete</button>
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
                    flashMessage('warning', 'Select product first');
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

                let paidamount = parseFloat($(".previou-paid-amount").text().replace(/[^\d.]/g, '')) || 0;
                let discountamount = parseFloat($(".previou-discount-total").text().replace(/[^\d.]/g, '')) || 0;

                if(paidamount != null && paidamount != 0 && discountamount != null && discountamount != 0){
                    let paidanddiscount = paidamount + discountamount;
                    let grandTotalAdmin = adminsubtotal - paidanddiscount;
                    let grandTotal = subtotal - paidanddiscount;
                    setTotalValue(grandTotalAdmin,grandTotal);
                }else if(paidamount != null && paidamount != 0){
                    let grandTotalAdmin = adminsubtotal - paidamount;
                    let grandTotal = subtotal - paidamount;
                    setTotalValue(grandTotalAdmin,grandTotal);
                }else if(discountamount != null && discountamount != 0){
                    let grandTotalAdmin = adminsubtotal - discountamount;
                    let grandTotal = subtotal - discountamount;
                    setTotalValue(grandTotalAdmin,grandTotal);
                }else{
                    setTotalValue(adminsubtotal,subtotal);
                }
            }

            function setTotalValue(adminvalue,value){
                $(".admin_sub_total").text(`${currency}  ${adminvalue.toFixed(2)}`);
                $(".admin_grand_total").text(`${currency}  ${adminvalue.toFixed(2)}`);
                $(".sub-total").text(`${currency}  ${value.toFixed(2)}`);
                $(".grand-total").text(`${currency}  ${value.toFixed(2)}`);
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
                    let privusdiscount = parseFloat($card.find(".previou-discount-total").text().replace(/[^\d.]/g, '')) || 0;
                    let adminsubtotal = parseFloat($card.find(".admin_sub_total").text().replace(/[^\d.]/g, '')) || 0;
                    let subtotal = parseFloat($card.find(".sub-total").text().replace(/[^\d.]/g, '')) || 0;
                    let discount = parseFloat($card.find(".discount").val()) || 0;
                    let paidAmount = parseFloat($card.find(".paid-amount").val()) || 0;
                    let previousDue = parseFloat($card.find(".due_amount").val()) || 0;
                    let grandTotal = ((adminsubtotal + privusdiscount) - tax - discount);
                    let due_amount = grandTotal - paidAmount;

                    $card.find(".admin_grand_total").text(`${currency} ${grandTotal.toFixed(2)}`);
                    $card.find(".due_amount").attr("placeholder", `${due_amount.toFixed(2)}`);

                });
        });
    </script>
@endpush
