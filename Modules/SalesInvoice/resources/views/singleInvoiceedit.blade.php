@extends('layouts.app') @section('title', $title) @push('styles')
<link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/image-gallary.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/invoice.css') }}"> 
@section('content') 
  <div class="card">
    <div class="card-heading">
      <h3 class="p-2">Invoice Edit Form</h3>
    </div>
    <div class="card-body">
      <form id="salesForm" action="{{ route('admin.sale.update', ['id' => $salesinvoice->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf 
        @method('PUT')
        <div class="invoice-box">
          <table cellpadding="0" cellspacing="0">
            <tr class="top">
              <td colspan="4">
                <table>
                  <tr>
                    <td class="title">
                      <img src="https://www.sparksuite.com/images/logo.png" style="width:100%; max-width:300px;">
                    </td>
                    <td>Invoice :<input  class="inputStyle" type="text" name="invoice_id" value="{{ $salesinvoice->invoice_id}}"/><br> Created: <input type="date" class=" inputStyle discount text-end" name="create_date" value="{{ $salesinvoice->create_date }}" >
                       <br> Due: <input  class="inputStyle" type="date" class=" discount text-end" name="due_date" value="{{ $salesinvoice->due_date }}">
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr class="information">
              <td colspan="4">
                <table>
                  <tr class="container">
                    <td>
                      <label>Customer Name :</label>
                      <input   type="text" name="customer_name" value=" {{ $customer->customer_name}}"/>
                       <br>
                       <label>Customer Phone :</label>
                       <input  type="text" name="customer_phone" value=" {{ $customer->phone}}"/> 
                       <br> 
                       <label>Customer Address :</label>
                       <input  type="text" name="customer_address" value=" {{ $customer->address}}"/>
                      </td>
                    <td> Sparksuite, Inc. <br> 12345 Sunny Road <br> Sunnyville, CA 12345 </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr class="heading">
              <td colspan="3"> Payment Method </td>
              <td> Check # </td>
            </tr>
            <tr class="details">
              <td colspan="3"> Check </td>
              <td> 1000 </td>
            </tr>
            <tr class="heading">
              <td>
                <h2 class="search-data"></h2>
                 Item
              </td>
              <td> Unit Cost </td>
              <td> Quantity </td>
              <td> Total Price </td>
            </tr>
            @php
            $sub_total = 0;
          @endphp
          @foreach ($invoiceDetails as $item )
          <tr class="">
            <td>
              <input type="text" value=" {{ $item->description }}" name="description[]"  class="item-field fs-6"/>
              
            </td>
            <td>
              <input name="cost[]" type="number" value="{{ $item->cost }}" />
            </td>
            <td>
              <input name="qty[]" type="number" value="{{ $item->qty }}" />
            </td>
            <td> {{ $item->cost *  $item->qty}}</td>
           @php
              $sub_total += $item->cost *  $item->qty
           @endphp
          </tr>
        @endforeach
        <tr>
          <td colspan="4">
            <a class="btn-add-row ">add more Item</a>
          </td>
        </tr>
            <tr class="total">
              <td colspan="3">
                 <div class="suggestions"></div>
              </td>
              <td>
                <div class="card p-2 shadow-sm bg-light">
                  <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between">
                      <strong>Sub Total:</strong>
                      <span class="sub-total">৳ {{ $sub_total  }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                      <strong>Tax:</strong>
                      <input type="number" name="tax" class="form-control form-control-sm tax-amount text-end" value="{{ $salesinvoice->tax}}">
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                      <strong>Paid amount:</strong>
                      <input type="number" name="paid_amount" class="form-control form-control-sm paid-amount text-end" value="{{ $salesinvoice->paid_amount}}">
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                      <strong>Discount:</strong>
                      <input type="number" name="discount" class="form-control form-control-sm discount text-end" value="{{ $salesinvoice->discount}}">
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                      <strong>Previous Due:</strong>
                      <input type="number" name="previous_due" class="form-control form-control-sm previous-due text-end" value="{{ $salesinvoice->previous_due}}">
                    </li>
                    @php
                    $grand_total = $sub_total-($salesinvoice->paid_amount+$salesinvoice->discount)+($salesinvoice->tax+$salesinvoice->previous_due)
                    @endphp
                    <li class="list-group-item text-center bg-danger text-white "> Grand Total: <span class="grand-total">৳ {{ $grand_total  }}</span>
                    <li><button type="submit" class="btn btn-primary btn-sm mt-3">Update</button></li>
                
                    </li>
                  </ul>
                </div>
            </tr>
          </table>
          <hr>
          <div class="text-left">
          <button onclick="window.history.back()" type="submit" class="btn btn-primary btn-sm mt-3">Back</button>
          {{-- <button onclick="window.history.back()" type="submit" class="btn btn-info btn-sm mt-3">View</button>
          <button onclick="window.history.back()" type="submit" class="btn btn-danger btn-sm mt-3">Delete</button> --}}
          </div>
        </div>
      </form>
    
    </div>
  </div>
  @push('scripts')
    <script>
        $(document).ready(function() {
            const projectRedirectUrl = "{{ route('admin.salesinvoice.index') }}";
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
                <input type="text" value="" name="description[]" class="item-field fs-6"/>
            </td>
            <td>
                <input name="cost[]" type="number" value="" />
            </td>
            <td>
                <input name="qty[]" type="number" value="1" class="qty" />
            </td>
            <td class="total w-7">&#x9F3; 0.00</td>
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
        let itemValue = $(this).val().trim();
        let $suggestions = $(".suggestions");
        $suggestions.empty().hide();
        $.ajax({
            url: "{{ route('admin.product.sale.search') }}",
            method: 'GET',
            data: { query: itemValue },
            success: function(response) {
                if (response.length > 0) {
                    let products = response.filter(product => product.name.toLowerCase().includes(itemValue.toLowerCase()));
                    let product_cost = 0;
                    products.forEach(product => {
                        if (itemValue != '') {
                            product_cost = product.product_sales_qty;
                            $suggestions.append(`<div class='suggestion-item fs-6 bg-light text-dark'>${product.name}</div>`);
                            $suggestions.hide();
                        }
                    });
                    $suggestions.show();
                    $(".suggestion-item").on("click", function() {
                        rowIndex.find("input").val($(this).text());
                        let siblingInput = rowIndex.closest("tr.item").find("input[name='cost[]']");
                        siblingInput.val(product_cost);
                        totalCal(rowIndex.closest("tr.item"));
                        rowCount = $("tr.item").length;
                        calculateSubtotal();
                        $suggestions.empty().hide();
                    });
                } else {
                    $suggestions.hide();
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
        let total = cost * qty;
        $row.find(".total").text(`৳ ${total.toFixed(2)}`);
    }

    // Calculate sub total
    function calculateSubtotal() {
        let subtotal = 0;
        $("tr.item").each(function() {
            let totalValue = parseFloat($(this).find(".total").text().replace(/[^\d.]/g, '')) || 0;
            subtotal += totalValue;
        });
        $(".sub-total").text(`৳ ${subtotal.toFixed(2)}`);
    }

    // Total count
    $(document).on("focus keyup", "input[name='qty[]'], input[name='cost[]']", function() {
        totalCal(this);
        rowCount = $("tr.item").length;
        calculateSubtotal();
    });

    // Total amount dom insert
    function formatAsCurrency(amount) {
        return `&#x9F3; ${Number(amount).toFixed(2)}`;
    }

    // Summary calculate
    $('.card-summary').on("input focus change", ".tax-amount, .paid-amount, .discount, .previous-due", function() {
        let $card = $(this).closest(".card");
        let subtotal = parseFloat($card.find(".sub-total").text().replace(/[^\d.]/g, '')) || 0;
        let tax = parseFloat($card.find(".tax-amount").val()) || 0;
        let discount = parseFloat($card.find(".discount").val()) || 0;
        let paidAmount = parseFloat($card.find(".paid-amount").val()) || 0;
        let previousDue = parseFloat($card.find(".previous-due").val()) || 0;
        let grandTotal = (subtotal + tax + previousDue) - (discount + paidAmount);
        $card.find(".grand-total").text(`৳ ${grandTotal.toFixed(2)}`);
    });

    // customer search by number
    $('.customer_phone').on('input change ',function(){
      let inputValue  = $(this).val().trim()
      if (inputValue == "") {
        $("input[name='customer_name']").val('');
        $("input[name='customer_address']").val('');
        $("input[name='customer_phone']").val('');
        return;
      }

      $.ajax({
        url :"{{ route('admin.customer.search') }}",
        data: {
          input : inputValue
        },
        success : function(res){
          if(res.phone.includes(inputValue)){
            $("input[name='customer_name']").val(res.customer_name)
            $("input[name='customer_address']").val(res.address)
          }else if(inputValue==res.phone){
            $("input[name='customer_phone']").val(res.phone) 
          }
        },
        error : function(err){
          console.log(err)
        }
      })
    })
  });
</script>
