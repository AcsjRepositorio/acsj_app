@extends('adminlte::page')

@section('title', 'ticket')

@section('content_header')
<p>Dashboard</p>
@stop

@section('content')
<h1>Ticket(Financeiro)</h1>

<body>

    @foreach($orders as $order)
    <div class="container mt-5">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>Nº do pedido</th>
                    <th>Características do pedido</th>
                    <th>Data do pedido</th>
                    <th>Valor total</th>
                    <th>Status do pagamento</th>
                    <th>Método de pagamento</th>
                    <th>Cliente</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$order->order_id}}</td>
                    <td style="background-color:rgba(102, 56, 115, 0.23)">
                        @foreach ($order->meals as $meal)

                        <div class="accordion" id="mealAccordion{{$meal->id}}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{$meal->id}}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$meal->id}}" aria-expanded="true" aria-controls="collapse{{$meal->id}}">
                                        Detalhes do Prato: {{$meal->name}}
                                    </button>
                                </h2>
                                <div id="collapse{{$meal->id}}" class="accordion-collapse collapse show" aria-labelledby="heading{{$meal->id}}" data-bs-parent="#mealAccordion{{$meal->id}}">
                                    <div class="accordion-body" style="background-color: #ffff; padding: 15px; border-radius: 8px;">
                                        <div class="row mb-3 d-flex justify-content-between ">
                                            
                                            <div class="col-md-6 ">
                                                <strong>Prato:</strong>
                                            </div>
                                            <div class="col-md-6 ">
                                                <span>{{$meal->name}}</span>
                                            </div>
                                            
                                        </div>
                                        <div class="row mb-3 d-flex justify-content-between">
                                            <div class="col-md-6 ">
                                                <strong>Quantidade:</strong>
                                            </div>
                                            <div class="col-md-6 ">
                                                <span>{{$meal->pivot->quantity}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 d-flex justify-content-between" >
                                            <div class="col-md-6 ">
                                                <strong>Agendado:</strong>
                                            </div>
                                            <div class="col-md-6 ">
                                                <span>{{$meal->day_week_start}} - {{$meal->day_of_week}}</span>
                                            </div>
                                        </div>
                                     
                                    </div>




                                </div>
                            </div>
                        </div>
                        @endforeach

                        
                    </td>
                    <td>{{$order->created_at}}</td>
                    <td>€ {{$order->amount}}</td>
                    <td>{{$order->payment_status}}</td>
                    <td>{{$order->payment_method}}</td>
                    <td>
                        <div class="form-checkclass d-flex align-items-center mb-2"><strong>Nome:</strong> {{$order->customer_name}}</div>
                        <div class="form-checkclass d-flex align-items-center mb-2"><strong>Email:</strong> {{$order->customer_email}}</div>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
    @endforeach
</body>

@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@stop


<script>
    // Script para o acordeon das caracteristiccas do pedido no dashboard
    document.addEventListener('DOMContentLoaded', function() {
        const accordions = document.querySelectorAll('.accordion .accordion-collapse');
        accordions.forEach(accordion => {
            accordion.classList.remove('show'); 
        });
    });
</script>


<script>
            // Script para o acordeon das caracteristiccas do pedido no dashboard
            document.addEventListener('DOMContentLoaded', function() {
                const accordions = document.querySelectorAll('.accordion .accordion-collapse');
                accordions.forEach(accordion => {
                    accordion.classList.remove('show');
                });
            });
        </script>