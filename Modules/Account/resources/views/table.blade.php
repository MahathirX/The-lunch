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
    <tbody id="filter_date">
        @php
            $totalexpense = 0;
            $totaldiscount = 0;
            $totalorginalprofit = 0;
            $totalprofit = 0;
            $totalorginalgrandprofit = 0;
            $totalgrandprofit = 0;
        @endphp
        @forelse ($expenses as $key => $expense)
            <tr>
                <th>{{ convertToLocaleNumber($key + 1) }}</th>
                <td>{{ formatDateByLocale(\Carbon\Carbon::parse($expense['create_date'])->format('d-m-Y')) }}</td>
                <td>{{ convertToLocaleNumber($expense['amount'] ?? 0) }} {{ currency() }}</td>
                <td>{{ convertToLocaleNumber($expense['discount'] ?? 0) }} {{ currency() }}</td>
                <td>{{ convertToLocaleNumber((int) $expense['orginalprofit'] - (int) $expense['discount'])  }} {{ currency() }}</td>
                <td>{{ convertToLocaleNumber((int) $expense['profit'] - (int) $expense['discount']) }} {{ currency() }}</td>
                <td>{{ convertToLocaleNumber(((int) $expense['orginalprofit'] - (int) $expense['discount']) - (int) $expense['amount']) }} {{ currency() }}</td>
                <td>{{ convertToLocaleNumber(($expense['profit'] - (int) $expense['discount']) - (int) $expense['amount']) }} {{ currency() }}</td>
            </tr>
            @php
                $totalexpense            += $expense['amount'];
                $totaldiscount           += $expense['discount'];
                $totalorginalprofit      += (int) $expense['orginalprofit'] - (int) $expense['discount'];
                $totalprofit             += (int) $expense['profit'] - (int) $expense['discount'];
                $totalorginalgrandprofit += ( (int) $expense['orginalprofit'] - (int) $expense['discount']) - (int) $expense['amount'];
                $totalgrandprofit        += ((int) $expense['profit'] - (int) $expense['discount']) - (int) $expense['amount'];
            @endphp
        @empty
            <tr>
                <td class="text-danger text-center" colspan="7">{{ __f('No Expense Found Title') }}</td>
            </tr>
        @endforelse
        <tfoot class="tfoot">
            <tr>
                <th rowspan="1" colspan="1"></th>
                <th rowspan="1" colspan="1">{{ formatDateByLocale(\Carbon\Carbon::parse($startDate)->format('d-m-Y')).' ' . __f('To Title').' '. formatDateByLocale(\Carbon\Carbon::parse($endDate)->format('d-m-Y'))  }}</th>
                <th rowspan="1" colspan="1">{{ convertToLocaleNumber($totalexpense ?? 0) }} {{ currency() }}</th>
                <th rowspan="1" colspan="1">{{ convertToLocaleNumber($totaldiscount ?? 0) }} {{ currency() }}</th>
                <th rowspan="1" colspan="1">{{ convertToLocaleNumber($totalorginalprofit ?? 0) }} {{ currency() }}</th>
                <th rowspan="1" colspan="1">{{ convertToLocaleNumber($totalprofit ?? 0) }} {{ currency() }}</th>
                <th rowspan="1" colspan="1">{{ convertToLocaleNumber($totalorginalgrandprofit ?? 0) }} {{ currency() }}</th>
                <th rowspan="1" colspan="1">{{ convertToLocaleNumber($totalgrandprofit ?? 0) }} {{ currency() }}</th>
            </tr>
        </tfoot>
    </tbody>
</table>
