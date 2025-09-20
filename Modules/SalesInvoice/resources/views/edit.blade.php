@extends('layouts.app') @section('title', $title) @push('styles')
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
    <div class="card">
        <div class="card-heading">
            <h3 class="p-2">{{ __f('Sales Invoice Edit Title') }}</h3>
        </div>
        <div class="card-body">
            <form id="salesForm" action="{{ route('admin.sale.update', ['id' => $salesinvoice->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="invoice-box">
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
                                {{ __f('Invoice No Title') }} :<input class="inputStyle" type="text" name="invoice_id"
                                                value="{{ $salesinvoice->invoice_id }}" /><br>
                                                {{ __f("Created Date Title") }}: <input type="date" class="discount text-end" name="create_date"
                                                value="<?php echo date('Y-m-d'); ?>"><br />
                                                {{ __f("Due Title") }}: <input type="date" class="discount text-end" name="due_date"
                                                value="<?php echo date('Y-m-d'); ?>">
                                    <span id="hr-border"></span>
                                    {{ $customer->customer_name ?? '' }}
                                     <br> {{ $customer->phone ?? '' }}
                                            <br>
                                            {{ $customer->address ?? '' }}
                                            <span id="hr-border"></span>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-12 col-md-4 ">
                                <div class=" position-realtive" style="flex: 1;">
                                    <input class="form-control customer_phone" type="text" name="customer_phone"
                                        placeholder="{{ __f('Search by Number Placeholder') }}" autocomplete="off" value=" {{ $customer->phone }}">
                                    <span class="text-danger error-text customer_phone-error"></span>
                                    <div id="showCustomers"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="" style="flex: 1;">
                                    <input class="form-control customer_name" type="text" name="customer_name"
                                        placeholder="{{ __f('Customer Name Placeholder') }}" value=" {{ $customer->customer_name }}">
                                    <span class="text-danger error-text customer_name-error"></span>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="" style="flex: 1;">
                                    <input class="form-control customer_address" type="text"
                                        name="customer_address" placeholder="{{ __f('Customer Address Placeholder') }}" value=" {{ $customer->address }}">
                                    <span class="text-danger error-text customer_address-error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            @php
                                $sub_total = 0;
                            @endphp
                            @foreach ($invoiceDetails as $item)
                                @php
                                    $totalQty = DB::table('purchase_invoice_details')->where('product_id',$item->product_id)->where('batch_no',$item->batch_no)->first();
                                    $avaiableQty = $totalQty->qty - $totalQty->sales_qty;
                                    $totalEditedQty = $item->qty + $avaiableQty;
                                @endphp
                                <tr class="item">
                                    <td>
                                        <input type="text" value="{{ $item->product->name }}" name="productname[]"
                                            class="item-field " autocomplete="off"/>

                                    </td>
                                    <td class="d-none">
                                        <input name="product_qty[]" type="number" value="{{ $totalEditedQty }}" class="product_qty"/>
                                    </td>
                                    <td class="d-none">
                                        <input name="product_id[]" type="number" class="product_id" value="{{ $item->product_id }}"/>
                                    </td>
                                    <td class="d-none">
                                        <input name="batch_no[]" type="number" class="batch_no" value="{{ $item->batch_no }}"/>
                                    </td>
                                    <td>
                                        <input name="cost[]" type="number" value="{{ $item->cost }}" readonly />
                                    </td>
                                    <td>
                                        <input name="qty[]" type="number" value="{{ $item->qty }}" min="1" />
                                    </td>
                                    <td class="d-none">
                                        <input name="paidamount" type="hidden" value="{{ $salesinvoice->paid_amount }}" />
                                    </td>
                                    <td class="d-none">
                                        <input name="discount" type="hidden" value="{{ $salesinvoice->discount }}" />
                                    </td>
                                    <td class="total w-7">
                                        {{ $item->cost * $item->qty }} {{ currency() }}</td>
                                    <td>
                                        <button type="button" class="btn-delete-row btn btn-sm btn-danger">{{ __f('Delete Title') }}</button>
                                    </td>
                                    @php
                                        $sub_total += $item->cost * $item->qty;
                                    @endphp
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4">
                                    <a class="btn-add-row ">{{ __f('Add More Item Title') }}</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row px-2 position-relative">
                        <div class="col-12 col-md-8" id="invoiceInfoDiv">
                            <div class="suggestions p-1 d-none">
                            </div>
                            <div class="term-condition w-80 mt-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="attachment">{{ __f('Upload Attachment Title') }}:</label>
                                    @if (!empty($salesinvoice->attachment))
                                        @php
                                            $filePath = $salesinvoice->attachment;
                                            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                        @endphp

                                        <div class="mt-3">
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <a href="{{ asset($filePath) }}" target="_blank">
                                                    <img src="{{ asset($filePath) }}" alt="Attachment"
                                                        width="120" height="80px">
                                                </a>
                                            @elseif($extension === 'pdf')
                                                <a href="{{ asset($filePath) }}" download>{{ __f('Download PDF Title') }}</a>
                                            @else
                                                <a href="{{ asset($filePath) }}" target="_blank">{{ __f('View File Title') }}</a>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="input-group">
                                        <input class="form-control form-control-sm" type="file" name="attachment"
                                            id="attachment" />
                                        <label class="input-group-text" for="attachment"><i
                                                class="fas fa-upload"></i> {{ __f('Choose File Title') }}</label>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <span class="fw-semibold">{{ __f('Terms and Conditions Title') }}:</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $salesinvoice->terms == 1 ? 'checked' : '' }} name="terms"
                                            id="terms" value="1" checked>
                                        <label for="terms">{{ __f('I agree to the terms and conditions Title') }}</label>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="fw-semibold">{{ __f('Note Title') }}:</span>
                                    <textarea name="note" class="form-control" cols="30" rows="4" placeholder="{{ __f('Note Placeholder') }}">{!! $salesinvoice->note !!}</textarea>
                                </div>
                                <div class="mt-2">
                                    <x-form.selectbox parantClass="col-12 col-md-12 mb-3" class="form-control py-2"
                                        name="status" labelName="{{ __f('Status Title') }}" errorName="status">
                                        <option value="1" {{ $salesinvoice->status == '1' ? 'selected' : '' }}>
                                            {{ __f('Complete Status Title') }}</option>
                                        <option value="0" {{ $salesinvoice->status == '0' ? 'selected' : '' }}>
                                            {{ __f('Status Pending Title') }}</option>
                                    </x-form.selectbox>
                                </div>
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
                            <div class="card p-2 shadow-sm bg-light">
                                <div class="card-summary">
                                    <ul class="list-group list-group-flush small">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Previous Paid Amount Title') }} :</strong>
                                            <span class="previou-paid-amount">
                                                {{ $salesinvoice->paid_amount ?? 0 }} {{ currency() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Previous Due Amount Title') }} :</strong>
                                            @if ($salesinvoice->paid_amount != null && $salesinvoice->discount != null)
                                                <span
                                                    class="previou-due-amount">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->discount - $salesinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif ($salesinvoice->paid_amount != null)
                                                <span
                                                    class="previou-due-amount">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif($salesinvoice->discount != null)
                                                <span
                                                    class="previou-due-amount">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->discount }} {{ currency() }}</span>
                                            @else
                                                <span
                                                    class="previou-due-amount">
                                                    {{ $salesinvoice->sub_total }} {{ currency() }}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Previous Discount Amount Title') }} :</strong>
                                            <span
                                                class="previou-discount-total">
                                                {{ $salesinvoice->discount ?? 0 }} {{ currency() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Sub Total Title') }} :</strong>
                                            @if ($salesinvoice->paid_amount != null && $salesinvoice->discount != null)
                                                <span
                                                    class="previou-sub-total">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->discount - $salesinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif ($salesinvoice->paid_amount != null)
                                                <span
                                                    class="previou-sub-total">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif($salesinvoice->discount != null)
                                                <span
                                                    class="previou-sub-total">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->discount }} {{ currency() }}</span>
                                            @else
                                                <span
                                                    class="previou-sub-total">
                                                    {{ $salesinvoice->sub_total }} {{ currency() }}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Discount Title') }} :</strong>
                                            <input type="number" name="discount"
                                                class="form-control form-control-sm discount"
                                                value="{{ $salesinvoice->discount }}">
                                        </li>
                                        <span class="text-danger" id="invaid_discount"></span>
                                        <!-- Grand Total and Submit Button -->
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong> {{ __f('Grand Total Title') }} :</strong>
                                            @if ($salesinvoice->paid_amount != null && $salesinvoice->discount != null)
                                                <span class="grand-total">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->discount - $salesinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif ($salesinvoice->paid_amount != null)
                                                <span class="grand-total">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->paid_amount }} {{ currency() }}</span>
                                            @elseif($salesinvoice->discount != null)
                                                <span class="grand-total">
                                                    {{ $salesinvoice->sub_total - $salesinvoice->discount }} {{ currency() }}</span>
                                            @else
                                                <span class="grand-total">
                                                    {{ $salesinvoice->sub_total }} {{ currency() }}</span>
                                            @endif
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong>{{ __f('Paid amount Title') }} :</strong>
                                            <input type="number" name="paid_amount"
                                                class="form-control form-control-sm paid-amount">
                                        </li>

                                        <div class="input-group mt-2">
                                            <span class="input-group-text">{{ __f('Due amount Title') }}
                                                {{ currency() }} :</span>
                                            <input type="number" name="due_amount"
                                                class="form-control form-control-sm due_amount text-end">
                                            {{-- value="{{ $salesinvoice->due_amount }}" --}}
                                        </div>
                                    </ul>
                                </div>
                                <li class="text-end" style="list-style: none">
                                    <button type="submit" id="updated_btn" class="btn btn-primary btn-sm mt-3">
                                        <div class="spinner-border text-light d-none" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>{{ __f('Submit Title') }}
                                    </button>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                @if(Auth::check() && Auth::user()->role_id == 3)
                    const projectRedirectUrl = "{{ route('staff.salesinvoice.index') }}";
                @else
                    const projectRedirectUrl = "{{ route('admin.salesinvoice.index') }}";
                @endif

                $('#salesForm').on('submit', function(e) {
                    e.preventDefault();
                    $('.spinner-border').removeClass('d-none');
                    $('.error-text').text('');
                    let formData = new FormData(this);
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            if (res.status == 'success') {
                                flashMessage(res.status, res.message);
                                setTimeout(() => {
                                    window.location.href = projectRedirectUrl;
                                }, 100);
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
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Add new row
        $('.btn-add-row').on('click', function() {
            const newRow = `
        <tr class="item">
            <td>
                <input type="text" value="" name="productname[]" class="item-field fs-6" autocomplete="off"/>
            </td>
            <td>
                <input name="cost[]" type="number" value="" />
            </td>
            <td>
                <input name="qty[]" type="number" value="1" class="qty" min="1"/>
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
            $('table tbody tr.item:last input:first').focus();
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

            $.ajax({
                url: "{{ route('admin.product.sale.search') }}",
                method: 'GET',
                data: {
                    text: value
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
            let paidamount = parseFloat($(".previou-paid-amount").text().replace(/[^\d.]/g, '')) || 0;
            let discountamount = parseFloat($(".previou-discount-total").text().replace(/[^\d.]/g, '')) || 0;

            if (paidamount != null && paidamount != 0 && discountamount != null && discountamount != 0) {
                let paidanddiscount = paidamount + discountamount;
                let grandTotal = subtotal - paidanddiscount;
                setTotalValue(grandTotal);
            } else if (paidamount != null && paidamount != 0) {
                let grandTotal = subtotal - paidamount;
                setTotalValue(grandTotal);
            } else if (discountamount != null && discountamount != 0) {
                let grandTotal = subtotal - discountamount;
                setTotalValue(grandTotal);
            } else {
                setTotalValue(subtotal);
            }
        }

        function setTotalValue(value) {
            $(".previou-sub-total").text(`${value.toFixed(2)} {{ currency() }}`);
            $(".grand-total").text(`${value.toFixed(2)} {{ currency() }}`);
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
                let subtotal = parseFloat($card.find(".previou-sub-total").text().replace(/[^\d.]/g, '')) ||
                    0;
                let privusdiscount = parseFloat($card.find(".previou-discount-total").text().replace(
                    /[^\d.]/g, '')) || 0;
                let tax = 0;
                let discount = parseFloat($card.find(".discount").val()) || 0;
                let paidAmount = parseFloat($card.find(".paid-amount").val()) || 0;
                let previousDue = parseFloat($card.find(".due_amount").val()) || 0;
                let grandTotal = ((subtotal + privusdiscount) - tax - discount);
                let due_amount = grandTotal - paidAmount;
                // console.log(discount);
                // console.log(grandTotal);
                // console.log(due_amount);
                // console.log(privusdiscount);
                // if((discount == grandTotal ||  discount <= grandTotal)){
                //   $('#invaid_discount').html('');
                //   $('#updated_btn').prop('disabled', false);
                $card.find(".grand-total").text(
                    `${grandTotal.toFixed(2)} {{ currency() }}`);
                $card.find(".due_amount").val(`${due_amount.toFixed(2)}`);
                // }else{
                //   $('#invaid_discount').html('Invaid discount amount');
                //   $('#updated_btn').prop('disabled', true);
                // }
            });
    });
</script>
