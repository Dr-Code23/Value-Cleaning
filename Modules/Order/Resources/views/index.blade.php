<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="style.css" media="all"/>
    <style>
        @import 'https://fonts.googleapis.com/css2?family=Kanit:wght@400;500&display=swap';


        * {
            margin: 0;
            padding: 5px;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {

            color: #001028;
            background: #FFFFFF;
            font-family: 'Kanit', sans-serif;
            font-size: 12px;
        }

        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        #logo {
            margin-bottom: 10px;
        }

        #logo img {
            width: 100px;
        }

        h1 {
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url(dimension.png);
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: .9em;
        }

        #company {
            float: right;
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
            font-size: 14px;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 2px solid #C1CED9;

        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.total {
            font-size: 1.2em;
        }

        table td.grand {
            border-top: 1px solid #5D6975;;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }

        .h1 {
            color: white;
            background: #012169;
        }

        .h3 {
            color: #012169;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="https://valucleaning.erp-everest.com/storage/app/public/Valu Clean Logo 01.png" alt=""></div>
    <h1 class='h1'>INVOICE {{$order->order_code}}</h1>
    <div id="company" class="clearfix">

        <div id="logo">
            <img src="https://valucleaning.erp-everest.com/storage/app/public/Valu Clean Logo 01.png" alt="">
            <h3 class='h3'>Value Clean</h3>
        </div>
    </div>

    <div id="project">
        <div><span>CLIENT : </span> {{$user->name}}</div>
        </br>


        <div><span>ADDRESS : </span> {{$order->address}}</div>
        </br>


        <div><span>EMAIL : </span> <a href="{{$user->email}}">{{$user->email}}</a></div>
        </br>


        <div><span>DATE : </span> {{$date}}</div>
    </div>
</header>
<main>
    <table>
        <thead>
        <tr>
            <th class="service">Order</th>
            <th class="desc">Name</th>
            <th class="desc">PRICE</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td class="service"> Repeat: {{$order->repeat}}
                </br>
                </br>
                </br>
                Date: {{$order->date}}
                </br>
                </br>
                </br>

                Time {{$order->time}}
            </td>

        </tr>
        <tr>
            <td class="service">Service Name :</td>
            <td class="desc">{{$service->title}}</td>
            <td class="unit">{{$service->price}} $</td>
        </tr>
        <td class="service">Extra Service Name :</td>

        @foreach($order->sub_services as $subService)
            <tr>
                <td class="desc"></td>


                <td class="desc">{{$subService->title}}</td>
                <td class="unit">{{$subService->price}} $</td>

            </tr>
        @endforeach
        <td class="service">requirement :</td>

        @foreach($order->requirements as $requirement)
            <tr>
                <td class="desc"></td>

                <td class="desc">{{$requirement->title}}</td>
                <td class="unit">{{$requirement->requirement_price}} $</td>


                <td class="unit"> Count :{{$count->count ?? 0 }}</td>


            </tr>
        @endforeach
        <tr>
            <td colspan="4">SUBTOTAL</td>
            <td class="total">{{$order->total_price}} $</td>
        </tr>
        <tr>
            <td colspan="4">discount</td>
            <td class="total">{{$offer->offer_percent ?? 0}}%</td>
        </tr>
        <tr>
            <td colspan="4" class="grand total">GRAND TOTAL</td>
            <td class="grand total">{{$order->total_price}} $</td>
        </tr>
        </tbody>
        <div>
            Thank You ..
        </div>
    </table>
</main>
<h3>
    <footer class='h1'>
        Dr.Code for Software and Electronic Systems <a href="https://doctor-code.net">Dr.Code</a>
    </footer>
</h3>

</body>
</html>
