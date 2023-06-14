@extends('prints.layout.app', ['title' =>  'Invoice'])

@section('content')
    <div>
        <div style="line-height: 1;">
            <h4 style="line-height: 1; text-transform: capitalize">{{ $payload->lease->tenant->name }}</h4>

            @if($payload->lease->tenant->address)
                <h5 style="line-height: 1; text-transform: capitalize">{{ $payload->lease->tenant->address }}</h5>
            @endif
            @if($payload->lease->tenant->pin)
                <h5 style="line-height: 1; text-transform: uppercase">Pin: {{ $payload->lease->tenant->pin }}</h5>
            @endif

        </div>

        <div style="margin-top: -4rem; float: right">
            <h4 style="line-height: 1;">{{ $payload->created_at->format('F j, Y') }}</h4>
            <h4 style="line-height: 1;">Invoice: {{ $payload->code }}</h4>
        </div>
    </div>
    <div class="clearfix"></div>

    <div style="width: 100%; margin-top: 2rem;">
        <table style="width: 100%">
            <thead>
            <tr style="background: #e3e3e3;">
                <th scope="col">#</th>
                <th scope="col">Property</th>
                <th scope="col">House</th>
                <th scope="col" style="text-align: right">Amount <span style="font-size: 10px;" class="font-weight-light">(Ksh)</span>
                </th>
            </tr>
            </thead>
            <tbody>
                <tr style="text-transform: uppercase; margin-bottom: -3px;">
                    <td>1.</td>
                    <td style="text-align: center">{{ $payload->lease->house->property->name }}</td>
                    <td style="text-align: center">{{ $payload->lease->house->name }}</td>
                    <td style="text-align: right">{{ number_format($payload->amount, 2) }}</td>
                </tr>
            @if($payload->tax)
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: right">Sub total</td>
                    <td style="text-align: right">{{ number_format($payload->amount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: right">VAT 16%</td>
                    <td style="text-align: right">{{ number_format(($payload->tax_amount), 2) }}</td>
                </tr>
            @endif
            <tr style="margin-top: 2rem; background: #e3e3e3;">
                <td colspan="2"></td>
                <td style="text-align: right"><strong>TOTAL AMOUNT</strong></td>
                <td style="text-align: right"><strong>{{ number_format($payload->total_amount, 2) }}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div style="text-align: right; margin-top: 6px;">
        <p class="text-right">You were served by: &nbsp; <span class="font-weight-normal">{{ $payload->user->name }}</span></p>
    </div>

@endsection
