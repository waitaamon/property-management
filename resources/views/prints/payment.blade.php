<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html">
    <title>Document</title>
    <title>Customer Payment</title>
    <link rel="stylesheet" href="{{  public_path('css/bootstrap.min.css') }}">
</head>
<body>
<div class="" style="margin-left: -30px; margin-top: -40px; color: #000000 !important; ">
    <div class="row" style="">
        <div class="col-12">
            <h3 class="font-bold uppercase">{{ $setting->name }}</h3>
            @if(isset($setting->address)|| isset($setting->phone))
                <h5 class="h5 text-uppercase font-bold text-whitespace-pre-line" style="line-height: 1.1">
                    {{ $setting->address }}
                    <br>
                    {{ $setting->phone }}
                </h5>
            @endif
            <h4 class="h4 font-weight-bold text-uppercase">Payment Receipt</h4>
        </div>
    </div>
    <div class="mt-4">
        <div class="row">
            <div class="col-12">
                <h5 class="h5 font-weight-bold text-uppercase">RECEIPT NO: &nbsp; <span class="">{{ $payload->code }}</span></h5>
                <h5 class="h5 font-weight-bold text-uppercase">NAME: &nbsp; <span class="">{{ $payload->accountable->name }}</span></h5>

                <h4 class="h4 font-weight-bolder text-uppercase my-2 text-under"><u>Payments Details</u></h4>

                <h5 class="h5 font-weight-bold text-uppercase">Date: &nbsp; <span class="">{{ $payload->created_at->format('Y-m-d') }}</span></h5>
                <h5 class="h5 font-weight-bold text-uppercase">Type: &nbsp; <span class="">{{ $payload->bankAccount->name }}</span></h5>
                <h5 class="h5 font-weight-bold text-uppercase">Amount: &nbsp; <span class="">{{ number_format($payload->amount, 2) }}</span></h5>
            </div>
        </div>
    </div>
    <div class="">
        <div class="row">
            <div class="col-12">
                <p class="font-weight-bold">You were served by: {{ $payload->user->name }}</p>
                <p class="font-weight-bold text-sm">Receipt valid subject to confirmation</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
