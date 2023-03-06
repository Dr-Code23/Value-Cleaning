<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="card">
    <div class="card-header bg-black"></div>
    <div class="card-body">

        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <i class="far fa-building text-danger fa-6x float-start"></i>
                </div>
            </div>

            <div class="row text-center">
                <h3 class="text-uppercase text-center mt-3" style="font-size: 40px;">Invoice</h3>
            </div>

            <div class="row mx-3">
                <table>
                <tbody>
                <tr>
                    <td>Worke Aera</td>
                    <td><i class="fas fa-dollar-sign"></i>{{ $order->worke_aera ?? ''}} m</td>
                </tr>

                <tr>
                    <td>date</td>
                    <td><i class="fas fa-dollar-sign"></i>{{ $order->date ?? '' }}</td>
                </tr>

                <tr>
                    <td>time</td>
                    <td><i class="fas fa-dollar-sign"></i>{{ $order->time ?? '' }}</td>
                </tr>

                <tr>
                    <td>address</td>
                    <td><i class="fas fa-dollar-sign"></i>{{ $order->address ?? '' }}</td>
                </tr>

                <tr>
                    <td>repeat</td>
                    <td><i class="fas fa-dollar-sign"></i>{{ $order->repeat ?? '' }}</td>
                </tr>


                <tr>
                    <td>service name</td>
                    @foreach($order->services as $service)
                        <td><i class="fas fa-dollar-sign"></i>{{ $service->title ?? '' }}</td>
                    @endforeach                    </tr>
                <tr>
                    <td>Extra Service</td>
                    <td><i class="fas fa-dollar-sign"></i>{{ $order->subservices ?? '' }}</td>
                </tr>
                <tr>
                    <td>total_price</td>
                    <td><i class="fas fa-dollar-sign"></i>{{ $order->total_price ?? '' }}</td>
                </tr>
                </tbody>
                </table>

            </div>

            <hr>
            <div class="row">
                <div class="col-xl-8">
                    <ul class="list-unstyled float-end me-0">
                        <li><span class="me-3 float-start">Total Amount: {{ $order->total_price ?? '' }} USD</span>
                        </li>
                        <li>
                            <span class="me-5">Discount:</span><i class="fas fa-dollar-sign"></i> 500,00</li>

                    </ul>
                </div>
            </div>

            <div class="row mt-2 mb-5">
                <p class="fw-bold">Date: <span class="text-muted">{{$date}}</span></p>
            </div>

        </div>


    </div>
    <div class="card-footer bg-black"></div>
</div>
</body>
</html>
