<!DOCTYPE html>
<html lang="en" style="max-width: 100%;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APOLLO_RESERVATION_{{$contrato->num_contrato}}</title>
</head>
<style>
    p {
        margin: 0;
    }

    * {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    .policy-console {
        font-family: 'Inconsolata', monospace;
        text-transform: none;
        padding-right: 1.5rem !important;
        text-align: left;
    }

    .page-break {
        page-break-after: always;
    }
    .circulo {
        height: 11px;
        width: 11px;
        border-radius: 50%;
        border: solid;
        display: inline-block;
    }
    .linea {
        width: 16px;
        height: 0;
        border: 1px solid #000000;
        margin: 3px;
        display:inline-block;
    }
    .x {
        text-decoration-style: solid;
        font-weight: bold;
        font-size: 11px;
    }
    .triangulo {
        margin: 0;
        padding: 0;
        width: 0;
        height: 0;
        border: 0 solid transparent;
        border-right-width: 7px;
        border-left-width: 7px;
        border-bottom: 11px solid black;
    }

</style>

<body style="width: 100%; height: 10px;">
    <div  class="contrato" style="max-width: 100%;">

        <!-- HEADER -->
        <table style="width: 100%;">
            <th>
            <td style="width: 40%;">
                <img style="width: 200px" src="{{ 'assets/img/Price-Logo.png' }}">
            </td>
            <td style="width: 70%; text-align:right">
                <p >
                    <strong>
                        +52 (998) 269 2949
                    </strong>
                </p>
                <p style="font-size:16px;">
                    <strong>
                        administracion@priceautorental.com
                    </strong>
                </p>
                <p style="font-size:16px;">
                    <strong>
                        PLAYACARS S.A DE C.V.
                    </strong>
                </p>
            </td>

            </th>
        </table> <!-- END HEADER -->
        <br>
        <div style="width: 100%; display: table; background: #E6E6E6; padding: 15px;">
            <div style="display: table-row">
                <div style="display: table-cell;">
                    <p style="font-size:20px;"> <strong>Reservation information:</strong></p>
                </div>
            </div>
        </div>
        <br>
        <table style="width: 100%;">
            <tr style="font-size: 16px;">
                <!-- INFO Reservacion y cliente -->
                <td style="width: 70%; ">
                    <div style="width: 100%;">
                        <p><b>Rservation Id:</b></p>
                        <p>{{$contrato->num_contrato}}</p>
                    </div>
                    <br>
                    <br>
                    <div style="width: 100%;">
                        <p><b>Client Information:</b></p>
                        <p style="text-transform:uppercase;">{{$contrato->cliente->nombre}}</p>
                        <p>{{$contrato->cliente->telefono}}</p>
                        <p style="text-transform:uppercase;">{{$contrato->cliente->email}}</p>
                        <p style="text-transform:uppercase;">{{$contrato->cliente->direccion}}</p>
                    </div>

                </td> <!-- END INFO  Reservacion y cliente -->
                <!-- INFO complementario -->
                <td style=" width: 30%; vertical-align:baseline">
                    <div style="width: 100%; ">
                        <p><b>Pick up</b></p>
                        <p>{{date_format(date_create($contrato->fecha_salida), 'd-F-Y')}} <span>({{date_format(date_create($contrato->hora_salida), 'h:i a')}})</span></p>
                        <p>{{$contrato->salida->alias}}</p>
                    </div>
                    <div style="width: 100%; ">
                        <p><b>Drop off</b></p>
                        <p>{{date_format(date_create($contrato->fecha_retorno), 'd-F-Y')}} <span>({{date_format(date_create($contrato->hora_retorno), 'h:i a')}})</span></p>
                        <p>{{$contrato->retorno->alias}}</p>
                    </div>
                    <div style="width: 100%; ">
                        <p><b>Category:</b></p>
                        <p>{{$contrato->tarifa_modelo_label}}</p>
                    </div>
                    <div style="width: 100%; ">
                        <p><b>Daily Price:</b></p>
                        <p>$ {{number_format($contrato->cobranza_calc[0]["value"])}}</p>
                    </div>
                </td> <!-- END INFO complementario -->
            </tr>

        </table>
        <br>
        <div style="width: 100%; display: table; padding: 3px">
            <div style="display: table-row">
                <div style="display: table-cell; ">
                    @if ($contrato->con_descuento != null || $contrato->con_iva == 1 )
                        <p style="font-size:14px; text-align: right"> <strong>Subtotal</strong> $ {{number_format(intval($contrato->subtotal))}} MXN</p>
                    @endif
                    @if ($contrato->con_descuento != null)
                       <p style="font-size:14px; text-align: right"> <strong>Discont</strong> $ {{number_format($contrato->cobranza_calc[1]["amount"])}} MXN</p>
                    @endif
                    @if ( $contrato->con_iva == 1 )
                        <p style="font-size:14px; text-align: right"> <strong>IVA</strong> $ {{number_format(intval($contrato->iva_monto))}} MXN</p>
                    @endif
                </div>
            </div>
        </div>
        <div style="width: 100%; display: table; background: #E6E6E6; padding: 3px">
            <div style="display: table-row">
                <p style="font-size:14px; text-align: right"> <strong>Total</strong> $ {{number_format(intval($contrato->total))}} MXN</p>
            </div>
        </div>
        <br>
        <div>
            <div style="width: 100%;">
                <p style="font-size: 14px; font-weight: bold">CHARGES DESCRIPTION: </p>
            </div>
            <div style="width: 100%;">
                @foreach ($contrato->cobranza_reserva as $cobranza)
                    <p>
                        @if ($cobranza->tarjeta != null)
                            <span>{{$cobranza->tarjeta->c_type}} *{{$cobranza->tarjeta->c_cn4}} {{($cobranza->tipo == 1) ? "PRE-AUT" : "CHARGE" }}: {{$cobranza->cod_banco}} {{date_format(date_create($cobranza->fecha_cargo), 'd-F-Y')}} ${{$cobranza->monto_cobrado}} {{ $cobranza->moneda_cobrada }}  {{ ($cobranza->tipo_cambio_id) ? "|| " .$cobranza->moneda_cobrada . "/" . $cobranza->moneda . "= $" .$cobranza->tipo_cambio : "" }}</span>
                        @else
                            <span>CASH PAYMENT {{date_format(date_create($cobranza->fecha_cargo), 'd-F-Y')}} ${{$cobranza->monto_cobrado}} {{ $cobranza->moneda_cobrada }} {{ ($cobranza->tipo_cambio_id) ? "|| " .$cobranza->moneda_cobrada . "/" . $cobranza->moneda . "= $" .$cobranza->tipo_cambio : "" }} </span>
                        @endif
                    </p>
                @endforeach

            </div>
            <br>
        </div>
        <br>
        <p style="color:red; font-style:italic">The reservation is non-refundable. It is not possible to change the dates of the reservation. </p>
    </div>
</body>
</html>
