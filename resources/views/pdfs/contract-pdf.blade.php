<!DOCTYPE html>
<html lang="en" style="max-width: 100%;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contract</title>
</head>
<style>
    p {
        margin: 0;
    }

    * {
        font-family: Arial, Helvetica, sans-serif;
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
        font-size: 13px;
    }
    .triangulo {
        margin: 0;
        padding: 0;
        width: 0;
        height: 0;
        border: 0 solid transparent;
        border-right-width: 7px;
        border-left-width: 7px;
        border-bottom: 13px solid black;
    }


</style>

<body style="width: 100%;">
    <div  class="contrato" style="max-width: 100%;">
        <!-- HEADER -->
        <table style="width: 100%;">
            <th>
            <td style="width: 50%;">
                <img src="{{ 'assets/img/PDF-Logo.png' }}">
            </td>
            <td style="width: 20%; vertical-align:middle">
                <p style="font-size:14px;">
                    <strong>
                        ANEXO
                    </strong>
                </p>
            </td>
            <td style="width: 60%; vertical-align:bottom; text-align:right">

                <p style="font-size: 14px; "> <b> CONTRATO # :</b> <span style="font-size: 24px;"> <b> {{$contrato->num_contrato}}</b></span> </p>


            </td>
            </th>
        </table> <!-- END HEADER -->

        <!-- INFO CLIENTE Y ARRENDAMIENTO DEL VEHICULO -->
        <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <tr style="font-size: 8px;">
                <td style="width: 20%; border: 1px solid black; ">
                    <p style="text-transform:uppercase;">{{$contrato->cliente->nombre}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->cliente->direccion}}</p>
                    <p>{{$contrato->cliente->telefono}}</p>
                    <p style="text-transform:uppercase;"><b>{{$contrato->cliente->email}}</b></p>
                </td>
                <td style="width: 20%;border: 1px solid black; ">
                    <p style="text-transform:uppercase;"><b>Oficina de renta:  {{$contrato->salida->alias}}</b></p>
                    <p style="text-transform:uppercase;">{{$contrato->salida->direccion}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->salida->colonia}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->salida->municipio}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->salida->cp}}</p>
                </td>
                <td style="width: 12%; text-align:center; border: 1px solid black;">
                    <p><b>FECHA DE RENTA</b></p>
                    <p style="text-transform:uppercase;">{{date_format(date_create($contrato->fecha_salida), 'd-F-Y')}}</p>
                    &nbsp;
                    <p><b>HORA</b></p>
                    <p style="text-transform:uppercase;">{{date_format(date_create($contrato->hora_salida), 'h:i a')}}</p>
                </td>
                <td style="width: 20%; border: 1px solid black;">
                    <p style="text-transform:uppercase;"><b>Oficina de retorno:  {{$contrato->retorno->alias}}</b></p>
                    <p style="text-transform:uppercase;">{{$contrato->retorno->direccion}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->retorno->colonia}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->retorno->municipio}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->retorno->cp}}</p>
                </td>
                <td style="width: 15%; text-align:center; border: 1px solid black;">
                    <p><b>FECHA DE RETORNO</b></p>
                    <p style="text-transform:uppercase;">{{date_format(date_create($contrato->fecha_retorno), 'd-F-Y')}}</p>
                    &nbsp;
                    <p><b>HORA</b></p>
                    <p style="text-transform:uppercase;">{{date_format(date_create($contrato->hora_retorno), 'h:i a')}}</p>
                </td>
            </tr>
        </table><!-- END INFO CLIENTE Y ARRENDAMIENTO DEL VEHICULO -->


        <div style="border: 1px solid black;">
            <table style="width: 100%;">
                <tr style="font-size: 8px;">
                    <!-- <td style="width: 25%; ">
                        <p><b>TARIFAS PACTADAS</b></p>
                        <p><b>CDP:</b>&nbsp;PREPAGO02F 50% DCTP PREPAGO</p>
                    </td> -->
                    <td style="width: 33.3333333333%; text-align: left">
                        <p><b>CONFIRMACION #:</b>&nbsp;{{$contrato->confirmacion}}</p>
                        <p><b>RES LOCAL #:</b>&nbsp;{{$contrato->res_local}}</p>
                    </td>
                    <td style="width: 33.3333333333%; text-align: left">
                        <p><b>CANAL DE ORIGEN:</b>&nbsp;LOCAL</p>
                        <p><b>CÓDIGO DE TARIFA:</b>&nbsp;@if($contrato->tipo_tarifa == 'Apollo' ) <span>AP</span> @elseif ($contrato->tipo_tarifa == 'Hotel')<span>HT</span>@else <span>CS</span> @endif</p>
                    </td>
                    <td style="width: 33.3333333333%; text-align: left">
                        <p><b>NO. DE CLIENTE:</b>&nbsp;{{$contrato->cliente->num_cliente}}</p>
                        <p><b>CONTACTO:</b>&nbsp;@if($contrato->tipo_tarifa == 'Apollo' ) <span>AP LOCAL</span> @elseif ($contrato->tipo_tarifa == 'Hotel')<span>HT LOCAL</span>@else <span>CS LOCAL</span> @endif</p>
                    </td>
                </tr>
            </table>
            <br>

            <table style="width: 100%;">
                <tr style="font-size: 8px;">
                    <!-- INFO VEHICULO Y SEGUROS -->
                    <td style="width: 50%; ">
                        <div style="width: 50%; float:left">
                            <p><b>INFORMACION DEL VEHICULO</b></p>
                        </div>
                        <br>
                        <br>
                        <div style="width: 50%; float:left">
                            <p style="text-transform:uppercase;">@if($contrato->tipo_tarifa == 'Apollo' || $contrato->tipo_tarifa == 'Comisionista' ) <span>Categoría</span> @else <span> Clase</span>@endif RESERVADA</p>
                            <p style="text-transform:uppercase;">@if($contrato->tipo_tarifa == 'Apollo' || $contrato->tipo_tarifa == 'Comisionista' ) <span>Categoría</span> @else <span> Clase</span>@endif ASIGNADA</p>
                            <p>NO. DE VEHICULO</p>
                            <p>MARCA</p>
                            <p>MODELO</p>
                            <p>COLOR</p>
                            <p>NO. DE PLACAS</p>
                            <p>SERIAL NO.</p>
                            <p>CAPACIDAD DEL TANQUE</p>
                            <p>GASOLINA DE SALIDA</p>
                            <p>KILOMETRAJE DE SALIDA</p>
                        </div>

                        <div style="width: 50%; float:right">
                            <p style="text-transform:uppercase;"> {{$contrato->tarifa_modelo_label}}</p>
                            <p style="text-transform:uppercase;">@if($contrato->tipo_tarifa == 'Apollo' || $contrato->tipo_tarifa == 'Comisionista' ) <span>{{$contrato->vehiculo->tarifa_categoria->categoria}}</span> @else <span> {{$contrato->vehiculo->clase->clase}}</span>@endif</p>
                            <p style="text-transform:uppercase;">{{$contrato->vehiculo->codigo}}</p>
                            <p style="text-transform:uppercase;">{{$contrato->vehiculo->marca->marca}}</p>
                            <p style="text-transform:uppercase;">{{$contrato->vehiculo->modelo}}</p>
                            <p style="text-transform:uppercase;">{{$contrato->vehiculo->color}}</p>
                            <p style="text-transform:uppercase;">{{$contrato->vehiculo->placas}}</p>
                            <p style="text-transform:uppercase;">{{$contrato->vehiculo->num_serie}}</p>
                            <p style="text-transform:uppercase;">{{$contrato->vehiculo->cap_tanque}} LT</p>
                            <p style="text-transform:uppercase;">{{$contrato->cant_combustible_salida}}</p>
                            <p style="text-transform:uppercase;">{{$contrato->km_inicial}} KM</p>
                        </div>
                        <br>

                        <div style="width: 50%;">
                            <p style="font-size: 8px;">*No se autorizan conductores adicionales
                                sin la previa autorización escrita
                            </p>
                        </div>
                        <br>
                        <div style="width: 100%;">
                            <div style="font-size: 8px;">
                                <p>__________ACEPTADO LDW</p>
                                <p class="policy-console">
                                    El cliente es responsable por el 10% del valor del vehículo
                                    en caso de ACCIDENTE, DAÑO, o ROBO, incluye "Perdidad de uso"
                                    *En contratos con código de tarifa "WKL" la protección defensa a
                                    defensa (B2B) NO esta incluida a menos que sea aceptada o adquirida
                                </p>
                            </div>
                        </div>
                        <br>
                        <div style="width: 100%;">
                            <div style="font-size: 8px;">
                                <p>__________ACEPTADO B2B</p>
                                <p class="policy-console">
                                    Incremento o complemento de protección
                                    El cliente ahora esta protegido "Defensa a Defensa"
                                    en caso de ACCIDENTE, DAÑO o ROBO (NO cubre ROBO de llantas y/o rines)
                                </p>
                            </div>
                        </div>
                        <br>
                        <div style="width: 100%;">
                            <div style="font-size: 8px;">
                                <p>__________ACEPTADO ALI</p>
                                <p class="policy-console">
                                    Se incrementa la cobertura por daños a terceros hasta el valor
                                    de $5,000,000.00 pesos por evento
                                </p>
                            </div>
                        </div>
                        <br>
                        <div style="width: 100%;">
                            <div style="font-size: 8px;">
                                <p>__________ACEPTADOP PAI</p>
                                <p class="policy-console">
                                    Cubre gastos médicos en caso de accidente por $50,000.00
                                    por ocupante y hasta $250,000.00 pesos por evento.
                                </p>
                            </div>
                        </div>
                        <br>
                        <div style="width: 100%;">
                            <div style="font-size: 8px;">
                                <p>__________ACEPTADO RS ASSIST</p>
                                <p class="policy-console">
                                    Asistencia Premium. Incluye: Envio de llave, apertura
                                    de auto, envio de gasolina, cambio de neumatico ponchado y
                                    paso de corriente. No incluye costo de llave ni gasolina
                                </p>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div>
                            <div style="width: 100%;">
                                <p style="font-size: 8px; font-weight: bold">DESCRIPCION DE CARGOS Y PRE-AUTORIZACIONES: </p>
                            </div>
                            <br>
                            <div style="width: 100%;">
                                @foreach ($contrato->cobranza as $cobranza)
                                    <p>
                                        @if ($cobranza->tarjeta != null)
                                            <span>{{$cobranza->tarjeta->c_type}} *{{$cobranza->tarjeta->c_cn4}} {{($cobranza->tipo == 1) ? "PRE-AUT" : "CARGO" }}: {{$cobranza->cod_banco}} {{date_format(date_create($cobranza->fecha_cargo), 'd-F-Y')}} ${{$cobranza->monto}}</span>
                                        @else
                                            <span>PAGO EFECTIVO {{date_format(date_create($cobranza->fecha_cargo), 'd-F-Y')}} ${{$cobranza->monto}} </span>
                                        @endif
                                    </p>
                                @endforeach

                            </div>
                        </div>
                        <br>
                        <br>
                        <div>
                            <div style="width: 100%;">
                                <p>
                                    <b>GASOLINA:</b>&nbsp;PRECIO POR LITRO FALTANTE <b>$19.00</b> MXN MAS CARGO
                                    POR SERVICIO DE <b>$13.00</b> MXN POR LiTRO FALTANTE IMPUESTOS INCLUIDOS
                                </p>
                                <small><b>APLICABLE SI LA OPCION DE PREPAGO DE GAS NO FUE ADQUIRIDA</b></small>
                            </div>
                        </div>


                    </td> <!-- END INFO VEHICULO Y SEGUROS -->
                    <!-- INFO CARGOS -->
                    <td style=" width: 50%; vertical-align:baseline">
                        <div style="width: 100%; text-align:left;">
                            <p><b>TARIFA PUBLICA VIGENTE MXN</b></p>
                        </div>
                        <br>
                        <div style="width: 90%; display: table; border: 1px solid black; padding: 10px;">
                            <div style="display: table-row">
                                <div style="display: table-cell; text-align:left; width:43%;"> <p><b>DESCRIPCION DE LOS CARGOS</b></p> </div>
                                <div style="display: table-cell; text-align:center; width:28%;"> <p><b> ------ </b></p> </div>
                                <div style="display: table-cell; text-align:right; width:26%;"> <p><b>CARGO ESTIMADO</b></p> </div>
                            </div>
                        </div>
                        <div style="width: 90%; display: table;">
                            <div style="display: table-row">
                                <div style="display: table-cell;  width:43%"></div>
                                <div style="display: table-cell; text-align:right; width:14%;"> <p><b> PRECIO.UNIT </b></p> </div>
                                <div style="display: table-cell; text-align: center; width:12%;"> <p><b>DIA</b></p> </div>
                                <div style="display: table-cell; width:24%"></div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width: 100%; text-decoration: underline;"><p><b> TARIFAS TIEMPO Y KILOMETRAJE</b></p></div>
                                <div style="display: table-cell; ">  </div>
                                <div style="display: table-cell; "></div>
                                <div style="display: table-cell;  "></div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width:45%;"> DAYS / DIAS</div>
                                <div style="display: table-cell; text-align:right; "> ${{$contrato->cobranza_calc[0]["value"]}} </div>
                                <div style="display: table-cell; text-align:center;">X {{$contrato->cobranza_calc[0]["quantity"]}}</div>
                                <div style="display: table-cell; width:45%;  text-align:right;">${{$contrato->cobranza_calc[0]["amount"]}}</div>
                            </div>
                            @if ($contrato->con_descuento != null)
                                <div style="display: table-row">
                                    <div style="display: table-cell; width:45%;"> DESCUENTO / DISCOUNT</div>
                                    <div style="display: table-cell; text-align:right; "> {{ number_format($contrato->cobranza_calc[1]["quantity"]) }}%</div>
                                    <div style="display: table-cell; text-align:center;"></div>
                                    <div style="display: table-cell; width:45%;  text-align:right;">-${{$contrato->cobranza_calc[1]["amount"]}}</div>
                                </div>
                            @endif
                            @if (false)
                                <div style="display: table-row">
                                    <div style="display: table-cell; width: 50%; text-decoration: underline;"><p><b> CARGOS DE GAS</b></p></div>
                                    <div style="display: table-cell; ">  </div>
                                    <div style="display: table-cell; "></div>
                                    <div style="display: table-cell;  "></div>
                                </div>
                            @endif
                        </div>
                        <br>
                        @if ($contrato->cobros_extras != null)
                            <div style="width: 90%; display: table;">
                                <div style="display: table-row">
                                    <div style="display: table-cell;  width:43%"></div>
                                    <div style="display: table-cell; text-align:right; width:14%;"> <p><b> PRECIO.UNIT </b></p> </div>
                                    <div style="display: table-cell; text-align: center; width:12%;"> <p><b>DIA</b></p> </div>
                                    <div style="display: table-cell; width:35%"></div>
                                </div>
                                <div style="display: table-row">
                                    <div style="display: table-cell; width: 100%; text-decoration: underline;"><p><b> PRODUCTOS ADICIONALES</b></p></div>
                                    <div style="display: table-cell; ">  </div>
                                    <div style="display: table-cell; "></div>
                                    <div style="display: table-cell;  "></div>
                                </div>

                                @foreach ($contrato->cobros_extras as $cobroExtra)
                                    <div style="display: table-row">
                                        <div style="display: table-cell; width:45%;"> {{$cobroExtra['nombre']}}</div>
                                        <div style="display: table-cell; text-align:right; ">${{$cobroExtra['precio']}} </div>
                                        <div style="display: table-cell; text-align:center;">X {{$contrato->cobranza_calc[0]["quantity"]}}</div>
                                        <div style="display: table-cell; width:45%;  text-align:right;">${{$cobroExtra['precio'] * $contrato->cobranza_calc[0]["quantity"]}}</div>
                                    </div>
                                @endforeach
                            </div>

                        @endif
                        <div style="width: 90%; display: table; border: 1px solid black; padding: 10px;">
                            <div style="display: table-row">
                                <div style="display: table-cell; text-align:left; width:43%;"> <p><b>SUBTOTAL-2</b></p> </div>
                                <div style="display: table-cell; text-align:center; width:28%;"> <p><b>  </b></p> </div>
                                <div style="display: table-cell; text-align:right; width:26%;"> <p><b>${{$contrato->subtotal}}</b></p> </div>
                            </div>
                        </div>
                        <div style="width: 90%; display: table;">
                            <div style="display: table-row">
                                <div style="display: table-cell; width: 100%; text-decoration: underline;"><p><b> CUOTAS LOCALES E IMPUESTOS FEDERALES</b></p></div>
                                <div style="display: table-cell; ">  </div>
                                <div style="display: table-cell; "></div>
                                <div style="display: table-cell;  "></div>
                            </div>
                            @if ($contrato->con_iva == 1)
                                <div style="display: table-row">
                                    <div style="display: table-cell; width:45%;"> I.V.A / TAX</div>
                                    <div style="display: table-cell; text-align:left; ">  </div>
                                    <div style="display: table-cell; text-align:right;"></div>
                                    <div style="display: table-cell; width:45%;  text-align:right;">${{$contrato->iva_monto}}</div>
                                </div>
                            @endif

                        </div>
                        <div style="width: 90%; display: table; border: 1px solid black; padding: 10px;">
                            <div style="display: table-row">
                                <div style="display: table-cell; text-align:left; width:70%;"> <p><b>CARGOS ESTIMADOS TOTALES INICIALES X_______</b></p> </div>
                                <div style="display: table-cell; text-align:right; width:20%;"> <p><b>${{$contrato->total}}</b></p> </div>

                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; text-align:left; width:100%;">
                                    <p style="font-size: 6px;">*TODAS LAS CANTIDADES REFLEJADAS SON EN MONEDA NACIONAL (MXN)</p>
                                </div>
                            </div>
                        </div>
                    </td> <!-- END INFO CARGOS -->
                </tr>

            </table>
        </div>
        <div style="font-size: 8px;">
            <div style="width: 100%;">
                <p style="margin: 0; font-weight: bold">Información de los cargos totales:</p>
                <p style="text-transform: none;">
                    (1) Al firmar este contrato el cliente declara tener conocimiento de todas las condiciones establecidas y acepta el clausulado al reverso. <br>
                    (2) Los cargos son ESTIMADOS, el importe total a pagar del contrato aparecera al cierre del mismo. <br>
                    (3) Usted va a alquilar y devolver el vehículo en el momento y lugares indicados. Gasolina no reembolsable en prepago, EXCEPTO si se regresa con tanque lleno. <br>
                    (4) El cliente es responsable por infracciones de tránsito. <br>
                    (5) El vehículo pierde la cobertura de los seguros bajo las siguientes condiciones: <br>
                        <p style="padding-left: 1rem; text-align: justify">(a) Si el vehículo es manejado en caminos sinuosos y/o terraciería.</p>
                        <p style="padding-left: 1rem; text-align: justify">(b) Si el conductor maneja en estado de ebriedad y/o bajo la influencia de alguna droga.</p>
                        <p style="padding-left: 1rem; text-align: justify">(c) Una vez entregado el vehículo, el cliente es responsable por cualquier anomalía mecánica, eléctrica o las que resulten si el vehículo se manejó bajo condiciones serveras (previa revisión por personal autorizado).</p>
                        <p style="padding-left: 1rem; text-align: justify">(d) El vehículo deberá ser entregado en el horario estipulado en el contrato y bajo las condiciones en que se recibió el mismo, en caso de incumplimiento las tarifas serán: TARIFAS EXTRAORDINARIAS.</p>
                        <p style="padding-left: 1rem; text-align: justify">(e) El vehículo debe devolverse en las mismas condiciones den las cuales se recibió; el exceso de arena, cemento, tierra, en asienteos, tapetes, techo y/o cajuela; además de asientos mojasdos y vestiduras manchagas y/o quemadas (general un costo extra de $300.00).</p>
                </p>
                <p style="text-transform: none;">
                    <b>*EXCLUSIONES E INCLUSIONES GENERALES DE LAS COBERTURAS LDW / CDW / CDW-2 / LDW PACK / CDW PACK / CDW2 PACK / CDW2 - LDW0 / CDW - LDW0</b> <br>
                    1. No cubren perdida de placas, multas o llaves. No cubren daños en llangas, rines espejos leterales y cristales. <br>
                    2 . LDW PACK / LDW PACK US / Extensión a (B2B) / cubren "Defensa a Defensa" por lo que daños a llantas, rines, espejos laterales y cristales si lo cubren. No cubre robo de llantas ni Rines.
                    3. Cualquier protección se invalida en caso de que el cliente no reporte el accidente dentro de un periodo menor a 24 horas despues del siniestro, tambien se invalidan en caso de haber alcohol. <br>
                    estupefacientes y/o drogas involucradas. No cubre si se conduce en el camino no pavimentado o si existe negligencia por parte del cliente, como cualquier acto doloso, irresponsable o temerario.
                    4. Cualquier protección se invalida si existen conductores NO autorizados en el presente contrato. <br>
                    5. Ninguna Protección cubre Daño o perdida de GPS.
                </p>
            </div>
        </div>
        <div style="width: 100%; display: table;">
            <div style="display: table-row">
                <div style="display: table-cell;  width:45%; font-size: 8px; vertical-align:middle;">
                    <p style="text-transform:uppercase;">
                        <b>ELABORADO POR: {{$contrato->usuario->nombre. ' ' .$contrato->usuario->apellidos}}</b> &nbsp; {{date_format(date_create($contrato->created_at), 'd-F-Y H:i:s')}}
                    </p>
                    <p style="text-transform:uppercase;">
                        <b> ENVIADO POR:  {{$contrato->usuario->nombre. ' ' .$contrato->usuario->apellidos}}</b> &nbsp;  {{date_format(date_create(), 'd-F-Y H:i:s')}}
                    </p>
                </div>
                <div style="display: table-cell; text-align:center; width:55%;">
                    <img style="width:35%;" src="{{$contrato->firma_cliente}}" />
                    <p>{{$contrato->cliente->nombre}}</p>
                    <p style="font-size: 11px; text-align: center;border-top: 1px solid;letter-spacing: 7px;">
                        FIRMA DEL CLIENTE
                    </p>
                </div>
            </div>
        </div>
        <div style="align-content: center; border: 1px solid black;">
            <div style="width: 100%; padding: 10px;">
                <p style="text-transform: none; font-size: 10px;">
                    SERVICIO AL CLIENTE EN EL CAMINO: <b>+52 (999)-689-1510</b> &nbsp; (24 Hhrs / 7 dias a la Semana)
                </p>
            </div>
        </div>

    </div>
    <div class="page-break"></div>
    <div class="clausulas" style="max-width: 100%;">

        <table style="width: 100%;">
            <tr style="font-size: 5.8px;">
                <td style="width: 50%; border-right: 1px solid black; float:left;">
                    <p style="text-align: left;">Contrato de arrendamiento de veh&iacute;culos que celebran por una parte el Proveedor PLAYACARS S.A. DE C.V., representado en este acto por la persona cuyo nombre aparece en el Anexo de este contrato y en contraparte el Consumidor cuyo nombre aparece en el Anexo del presente instrumento, mismos que por medio del presente manifiestan su voluntad para obligarse de acuerdo al siguiente glosario, as&iacute; como a las declaraciones y cl&aacute;usulas que a continuaci&oacute;n se describen:</p>

                    <p style="text-align: center; font-size: 8px;"><br />
                    <strong>GLOSARIO</strong></p>

                    <p style="text-align: left;">(a) Consumidor: Persona f&iacute;sica o moral que obtiene en arrendamiento el uso y goce temporal del veh&iacute;culo objeto de este contrato, quien para efectos de este contrato recibir&aacute; el nombre de: arrendatario<br />
                    (b) Proveedor. Persona moral que ofrece en arrendamiento, el uso y goce temporal de bienes muebles a cambio de una contraprestaci&oacute;n cierta y determinada, quien para efectos de este contrato recibir&aacute; el nombre de: arrendador.<br />
                    (c) Veh&iacute;culo: Aqu&eacute;l bien mueble que es objeto material de este contrato, mismo que se encuentra descrito en el anexo de este contrato.<br />
                    (d) Conductor adicional: La persona que autorizada expresamente por el consumidor en el Anexo para estar en aptitud de conducir el veh&iacute;culo arrendado.</p>

                    <p style="text-align: center; font-size: 8px;"><br />
                    <strong>DECLARACIONES</strong></p>

                    <p style="text-align: left;"><br />
                    <strong>PRIMERA. DECLARA EL ARRENDADOR:</strong><br />
                    (a) Ser una persona moral mexicana, constituida conforme la legislaci&oacute;n nacional aplicable, seg&uacute;n consta en la escritura publica numero 148, de fecha 24 de marzo de 2008, exhibida ante la fe del(a) Abog. Jose Enrique Guti&eacute;rrez L&oacute;pez, Notario Publico numero 87 de la ciudad de M&eacute;rida, estado de Yucat&aacute;n, e inscrita en el Registro Publico de la Propiedad y del Comercio del Estado de Yucat&aacute;n, bajo el folio mercantil electr&oacute;nico numero 47135-1, con fecha de registro 12 de junio de 2008, con la denominaci&oacute;n Avasa Turismo Internacional, S.A. de C.V.<br />
                    (b) Que la persona que suscribe el presente contrato tiene capacidad para celebrarlo en t&eacute;rminos de la legislaci&oacute;n aplicable.<br />
                    (c) Que su Registro Federal de Contribuyente corresponde al n&uacute;mero: 00000000<br />
                    (d) Que pone a disposici&oacute;n del consumidor como l&iacute;nea de contacto para cualquier asunto relacionado con este contrato el numero telef&oacute;nico: 55-91-28-9000, en los siguientes horarios de atenci&oacute;n: 24 horas y la direcci&oacute;n de correo electr&oacute;nico siguiente: atencionclientes@avasa.com.mx, adem&aacute;s de los n&uacute;meros que aparecen en el Anexo.<br />
                    (e) Que cuenta con los recursos humanos, financieros y materiales para llevar a cabo las obligaciones emanadas de este acto jur&iacute;dico.<br />
                    (f) Que dentro de sus actividades se encuentra la de otorgar en arrendamiento veh&iacute;culos.<br />
                    (g) Que cuenta con las licencias y permisos requeridos por la ley para prestar el servicio correspondiente.<br />
                    (h) Qu&eacute; inform&oacute; al arrendatario los alcances y efectos jur&iacute;dicos del presente contrato.<br />
                    (i) Inform&oacute; al consumidor el monto total a pagar por la operaci&oacute;n de arrendamiento, los servicios y coberturas que incluye, as&iacute; como restricciones que, en su caso, son aplicables para la operaci&oacute;n de este contrato.</p>

                    <p style="text-align: left;"><br />
                    <strong>SEGUNDA. DECLARA EL ARRENDATARIO:</strong></p>

                    <p style="text-align: left;">(a) Llamarse seg&uacute;n lo anotado en el Anexo de este contrato, para lo cual se identifica con los documentos que se se&ntilde;alan en ese mismo Anexo, as&iacute; como contar con la capacidad legal para cumplir con las obligaciones contenidas en este instrumento contractual.<br />
                    (b) Que cuenta con la capacidad legal, en t&eacute;rminos de las leyes aplicables, para obligarse bajo los t&eacute;rminos y condiciones contenidos en este contrato.<br />
                    (c) Qu&eacute; es su deseo contratar el arrendamiento objeto de este contrato, en los t&eacute;rminos y condiciones que se establecen en este documento.<br />
                    (d) Qu&eacute; sus generales corresponden a las anotadas en el anexo de este contrato.<br />
                    (e) Qu&eacute; se&ntilde;ala como domicilio, n&uacute;mero telef&oacute;nico de contacto y correo electr&oacute;nico para recibir notificaciones de cualquier asunto relacionado con este contrato los que aparecen en el Anexo.</p>

                    <p style="text-align: center; font-size: 8px;"><br />
                    <strong> CL&Aacute;USULAS</strong></p>

                    <p style="text-align: left;"><br />
                    PRIMERA. Consentimiento. Por medio del presente contrato, el arrendador se obliga a conceder el uso y goce temporal del veh&iacute;culo, por lo que el arrendatario deber&aacute; pagar un precio cierto y determinado, mismo que se establece en el Anexo, el cual se agrega al presente instrumento para formar para del mismo.<br />
                    SEGUNDA. Objeto. El objeto material de este contrato es el veh&iacute;culo que se encuentra descrito en el Anexo de este contrato, por lo que las caracter&iacute;sticas, condiciones, refacciones y documentos generales del veh&iacute;culo arrendado se encuentran detalladas en el documento mencionado.<br />
                    TERCERA. Condiciones del veh&iacute;culo arrendado. El arrendatario recibe de conformidad el veh&iacute;culo arrendado, el cual, como le fue mostrado se encuentra en &oacute;ptimas condiciones mec&aacute;nicas y de carrocer&iacute;a, las cuales se mencionan en el inventario respectivo, mismo que aparece en el Anexo del presente instrumento. Acordando las partes que el veh&iacute;culo se entregue con el kilometraje sellado, el uso y goce del veh&iacute;culo se destinar&aacute; exclusivamente al transporte del arrendatario y sus acompa&ntilde;antes. Cualquier otro uso y goce del veh&iacute;culo deber&aacute; se&ntilde;alarse por escrito para la debida informaci&oacute;n de las partes. As&iacute; mismo, el arrendatario recibe, exceptuando los vicios ocultos, el veh&iacute;culo a su entera satisfacci&oacute;n por lo que se obliga, en su caso, a pagar al arrendador a la terminaci&oacute;n del contrato, a precios de agencia, el o los faltantes o desperfectos de accesorios y partes del veh&iacute;culo arrendado al momento de la entrega del mismo. Para tal efecto. el arrendador deber&aacute; informar al correo electr&oacute;nico del arrendatario que aparece en el anexo, el costo de las reparaciones dentro de las 24 horas posteriores al se&ntilde;alamiento de los faltantes o desperfectos de accesorios y partes del veh&iacute;culo arrendado y podr&aacute; utilizar el monto del dep&oacute;sito en garant&iacute;a a que se refiere la cl&aacute;usula octava para cubrir dicho pago. En caso de que los costos de la reparaci&oacute;n excedan el monto del dep&oacute;sito de garant&iacute;a referido, el arrendatario se obliga a cubrir dichos excedentes tan pronto el arrendador le informe sobre el monto de los mismos.<br />
                    CUARTA. Lugar de entrega y recepci&oacute;n del veh&iacute;culo. El arrendador deber&aacute; entregar el veh&iacute;culo arrendado en el lugar se&ntilde;alado en el Anexo del presente contrato, respetando en la entrega el mismo el d&iacute;a y hora se&ntilde;alada en el Anexo del presente contrato. El arrendatario, al t&eacute;rmino de la vigencia del presente documento, deber&aacute; entregar el veh&iacute;culo, en las mismas condiciones en que lo recibi&oacute; exceptuando el desgaste por el uso, en el d&iacute;a y las horas se&ntilde;aladas para tal efecto, oblig&aacute;ndose a entregar el veh&iacute;culo al arrendador en el lugar determinado por las partes para tal efecto, en t&eacute;rminos de la cl&aacute;usula Novena del presente contrato,<br />
                    QUINTA. Plazo del arrendamiento. La vigencia de este contrato ser&aacute; la se&ntilde;alada en el Anexo del presente documento, la cual no podr&aacute; ser prorrogada sino con el pleno consentimiento de ambas partes expresado en un nuevo contrato de arrendamiento.<br />
                    SEXTA. Precio del arrendamiento. El arrendatario, por el uso y goce temporal del veh&iacute;culo arrendado, deber&aacute; pagar una cantidad cierta y determinada en moneda nacional sin perjuicio que las partes puedan acordar el pago en moneda extranjera conforme a las leyes aplicables, la cual se encuentra enunciada en el Anexo de este contrato. El arrendador se obliga a no exigir el cobro de ning&uacute;n cargo que no est&eacute; considerado en el presente contrato.<br />
                    S&Eacute;PTIMA. Modalidades de pago. El arrendatario podr&aacute; pagar la renta del veh&iacute;culo al contado en el domicilio del arrendador, con tarjeta bancaria, transferencia electr&oacute;nica o cualquier otra forma de pago mediante acuerdo de las partes. El precio total del arrendamiento se calcular&aacute; tomando en cuenta el costo por renta diario o por kilometraje, de acuerdo a lo solicitado por el consumidor y con las modalidades que acuerden las partes, mismas que se establecen en el Anexo. La renta empezar&aacute; a computarse desde el momento en que el consumidor se encuentre en plena disposici&oacute;n del veh&iacute;culo arrendado y hasta la fecha en que lo reciba el arrendatario a su entera satisfacci&oacute;n, por lo que el arrendador deber&aacute; hacer del conocimiento el precio estimado que el arrendatario deber&aacute; pagar por el servicio y que aparecer&aacute; se&ntilde;alado en el Anexo del presente contrato.<br />
                    En caso de que la arrendataria hubiera contratado el arrendamiento del veh&iacute;culo por kil&oacute;metros recorridos, estos se determinar&aacute;n por la lectura del kilometraje, registrado en el dispositivo instalado de f&aacute;brica en el veh&iacute;culo (od&oacute;metro). El kilometraje inicial registrado en el od&oacute;metro aparece en el anexo. Las partes estipulan que s&iacute;, durante el t&eacute;rmino del arrendamiento, sobreviene alg&uacute;n desperfecto o la rotura de los protectores de dicho sistema, por culpa o negligencia del arrendatario, la renta se calcular&aacute; tomando en cuenta la tarifa de renta por d&iacute;a que se establece en el Anexo de este contrato, durante el tiempo en que el veh&iacute;culo est&eacute; en posesi&oacute;n del arrendatario.<br />
                    OCTAVA. Dep&oacute;sito en garant&iacute;a. El arrendatario se obliga a entregar al arrendador la cantidad se&ntilde;alada en el Anexo de este contrato como dep&oacute;sito en garant&iacute;a del cumplimiento de la obligaci&oacute;n principal de pago. La garant&iacute;a tambi&eacute;n puede hacerse mediante un cargo en la tarjeta bancaria que el arrendatario proporcione o en cualquier otra modalidad que acepte el arrendador, misma que ser&aacute; determinada en el Anexo del presente contrato. En consecuencia, el arrendador deber&aacute; a expedir un recibo por dicha cantidad en que conste: el nombre o raz&oacute;n social de la misma, fecha e importe del dep&oacute;sito, nombre y firma de la persona que lo recibe. Este recibo servir&aacute; de comprobante de canje para que al t&eacute;rmino del contrato el arrendador entregue la cantidad depositada dentro de las 24 horas siguientes a la recepci&oacute;n del veh&iacute;culo de conformidad, en caso contrario dicho dep&oacute;sito se aplicar&aacute; a solventar los saldos si los hubiere o a pagar las reposiciones de faltantes y la reparaci&oacute;n de desperfectos, cuando hayan sido debidamente acreditados por el arrendador, en la inteligencia que este &uacute;ltimo podr&aacute; exigir, judicial o extrajudicialmente, el pago de una cantidad adicional si el dep&oacute;sito fuere insuficiente para cubrir la reposici&oacute;n de faltantes y la reparaci&oacute;n de saldos.<br />
                    NOVENA. Devoluci&oacute;n del veh&iacute;culo. El arrendatario se obliga a devolver al t&eacute;rmino de la vigencia del presente contrato el veh&iacute;culo arrendado en las mismas condiciones en que lo recibi&oacute; exceptuando el desgaste proveniente del uso normal del veh&iacute;culo durante el arrendamiento. Las partes acuerdan en que la entrega del veh&iacute;culo arrendado se lleve a cabo en la fecha, lugar y hora determinados en el Anexo de este contrato. En caso de que el veh&iacute;culo no sea entregado en los t&eacute;rminos se&ntilde;alados, la arrendataria podr&aacute; entregado posteriormente, previo acuerdo de las partes, pagando por este retraso el importe de la renta conforme a la tarifa indicada en el Anexo por el tiempo que tarde en entregar el veh&iacute;culo en la fecha y hora determinados; si el retraso en la entrega del veh&iacute;culo corresponde a la hora, el arrendatario s&oacute;lo estar&aacute; obligado a pagar la parte proporcional del incumplimiento.<br />
                    Cuando el veh&iacute;culo no fuere devuelto en el lugar se&ntilde;alado en el Anexo, el arrendatario podr&aacute; devolverlo en cualquier otra oficina del arrendador. En este caso, el arrendatario deber&aacute; cubrir el costo que se genere con motivo del traslado del veh&iacute;culo devuelto al domicilio se&ntilde;alado en el Anexo. En cualquier otro supuesto, el arrendatario deber&aacute; pagar el traslado del veh&iacute;culo, el monto de la renta que resulte y los costos adicionales que se generen con motivo de la recuperaci&oacute;n del veh&iacute;culo.<br />
                    En caso de que el arrendatario no entregue el veh&iacute;culo arrendado en el t&eacute;rmino acordado en el Anexo, el arrendador deber&aacute; contactado mediante mensaje por v&iacute;a telef&oacute;nica y por correo electr&oacute;nico para hacerle saber que el arrendamiento ha concluido y que se le requiere la entrega del veh&iacute;culo. En caso de que el arrendatario exprese su deseo de extender el plazo del arrendamiento, deber&aacute; presentar garant&iacute;a suficiente para el pago de la extensi&oacute;n del arrendamiento a satisfacci&oacute;n del arrendador. De lo contrario, el arrendador podr&aacute; dar por terminado este contrato, recuperando el veh&iacute;culo en las condiciones y estado en que se localice, siendo responsable la arrendataria del pago de la pena convencional correspondiente, m&aacute;s los gastos de recuperaci&oacute;n del veh&iacute;culo debidamente comprobados por el arrendador. Adicionalmente, el arrendador podr&aacute; ejercer las acciones civiles o penales que procedan conforme a su inter&eacute;s convenga, y de acuerdo a lo que las circunstancias del caso demanden. Transcurridas 5 horas posteriores al env&iacute;o del requerimiento de entrega sin que se obtenga respuesta del arrendatario y sin que se haya entregado el veh&iacute;culo arrendado, el arrendador podr&aacute; formular la denuncia ante la autoridad competente a fin de que localice el veh&iacute;culo e investigue la posible comisi&oacute;n de un delito.<br />
                    En cualquiera de los supuestos mencionados en el p&aacute;rrafo precedente, la arrendadora no ser&aacute; responsable de las pertenencias que se encuentren dentro del veh&iacute;culo arrendado, por lo que la arrendataria libera a la arrendadora de cualquier responsabilidad por pertenencias que haya dejado dentro del veh&iacute;culo arrendado.<br />
                    </p>
                </td>
                <td style="width: 50%; float:right; vertical-align:baseline">
                    <p style="text-align: left;">
                        DECIMA. Prohibici&oacute;n al veh&iacute;culo arrendado de salir de la Rep&uacute;blica. Sin el previo consentimiento por escrito del arrendador, el veh&iacute;culo arrendado no podr&aacute; salir de los l&iacute;mites de la Rep&uacute;blica Mexicana, tampoco podr&aacute; ser trasladado de una ciudad a otra por v&iacute;a mar&iacute;tima tanto dentro como afuera de la Rep&uacute;blica Mexicana; en caso de incumplimiento a lo anteriormente se&ntilde;alado, el arrendador podr&aacute; este contrato, recuperando el veh&iacute;culo en las condiciones y estado en que se localice, siendo responsable la arrendataria del pago de la pena convencional correspondiente, m&aacute;s de los gastos de recuperaci&oacute;n del veh&iacute;culo debidamente comprobados por el arrendador.<br>
                        D&Eacute;CIMA PRIMERA. Derechos y obligaciones de las partes. Los contratantes se reconocen como derechos exigibles, el cumplimiento de todas las disposiciones del presente contrato, normando su consentimiento por la observancia de las siguientes obligaciones:<br />
                        En el cumplimiento del presente contrato el arrendador se obliga a:<br />
                        (a) Entregar el veh&iacute;culo arrendado en &oacute;ptimas condiciones de uso, considerando el combustible necesario para tal efecto; el d&iacute;a, hora y lugar acordado por las partes.<br />
                        (b) Recibir el veh&iacute;culo con el mismo nivel de combustible con el que lo entreg&oacute; al arrendatario, de conformidad con lo que indique el medidor de combustible del veh&iacute;culo arrendado y que quedar&aacute; establecido en el Anexo del presente instrumento. En caso de que el Arrendatario no lo devuelva con la misma cantidad de combustible, pagar&aacute; al arrendador el costo del combustible faltante al precio de mercado m&aacute;s el cargo por el servicio de reabastecimiento se&ntilde;alado en el Anexo.<br />
                        (c) A recibir el veh&iacute;culo arrendado, se&ntilde;alando al arrendatario, de ser el caso, que el veh&iacute;culo lo recibe a su entera satisfacci&oacute;n; de lo contrario deber&aacute; manifestar en el acto de recepci&oacute;n los motivos de su proceder.<br />
                        (d) Devolver al arrendatario en el tiempo estipulado para tal efecto, la cantidad otorgada en dep&oacute;sito en garant&iacute;a en los t&eacute;rminos establecidos en la cl&aacute;usula octava del presente contrato.<br />
                        Para los efectos de este contrato, son obligaciones del arrendatario:<br />
                        (a) Pagar al arrendador la renta convenida del veh&iacute;culo arrendado de manera puntual, sin requerimiento de pago y en las condiciones establecidas en el presente contrato.<br />
                        (b) Conducir, en todo momento el veh&iacute;culo arrendado, bajo el amparo de la licencia respectiva, otorgada por las autoridades competentes; respetando los Reglamentos y Leyes de Tr&aacute;nsito en el &aacute;mbito Federal, Local o Municipal. El arrendatario &uacute;nicamente deber&aacute; permitir que conduzcan el veh&iacute;culo arrendado las personas que est&eacute;n expresamente autorizadas para hacerlo en el Anexo del presente contrato.<br />
                        (c) No manejar el veh&iacute;culo en estado de ebriedad o bajo la influencia de drogas.<br />
                        (d) No hacer uso del veh&iacute;culo en forma lucrativa, ni subarrendado.<br />
                        (e) No utilizar el veh&iacute;culo arrendado para arrastrar remolques y no sobrecargado debi&eacute;ndolo usar conforme a su resistencia y capacidad normal.<br />
                        (f) Conservar el veh&iacute;culo en el estado que lo recibi&oacute;, exceptuando el desgaste normal del uso.<br />
                        (g) No conducir ni transportar en el interior del veh&iacute;culo materias explosivas o inflamables, drogas o estupefacientes.<br />
                        (h) Pagar el importe de las sanciones que le fueran impuestas por violaci&oacute;n a los Reglamentos de Tr&aacute;nsito o a cualquier otra, a&uacute;n despu&eacute;s de concluida la vigencia del contrato, si la infracci&oacute;n se origin&oacute; durante el tiempo en que estuvo el veh&iacute;culo arrendando a disposici&oacute;n del arrendatario.<br />
                        (i) No utilizar el veh&iacute;culo de manera diferente a lo pactado, ni de manera alguna que implique da&ntilde;os al veh&iacute;culo por conducido en forma inadecuada, por hacerlo transitar por v&iacute;as no adecuadas conforme a las caracter&iacute;sticas del autom&oacute;vil o por participar en carreras u otras pruebas. Tampoco podr&aacute; conducido por v&iacute;as que se encuentren inundadas.<br />
                        (j) No subarrendar a terceros el veh&iacute;culo objeto del presente contrato sin previo consentimiento del arrendador.<br />
                        (k) Proporcionar al arrendador informaci&oacute;n veraz y documentaci&oacute;n original con motivo de la celebraci&oacute;n del presente contrato.<br />
                        (l) Revisar en forma peri&oacute;dica los niveles de aceite en el motor, del agua del radiador y revisar la presi&oacute;n del aire de las llantas del veh&iacute;culo. No realizar reparaci&oacute;n alguna al veh&iacute;culo, salvo previa autorizaci&oacute;n del arrendador.<br />
                        D&Eacute;CIMA SEGUNDA. Cobertura del veh&iacute;culo. El arrendador ofrecer&aacute; en arrendamiento veh&iacute;culos con una cobertura por responsabilidad civil frente a terceras con un l&iacute;mite m&aacute;ximo de $350,000 (trescientos cincuenta mil pesos 00/100 m.n.). En caso de siniestro el arrendatario ser&aacute; responsable de solventar los gastos que se generen con motivo del accidente, incluidos, en su caso, el deducible de la cobertura y cualquier otro gasto que surja con motivo del incidente y que exceda el monto de la cobertura existente o que no est&eacute; amparado por la misma. Esta responsabilidad se mantendr&aacute; mientras el veh&iacute;culo arrendado se encuentre a disposici&oacute;n del arrendatario. Corresponde al arrendador informar al arrendatario los t&eacute;rminos y condiciones en que operar&aacute; la cobertura, sin embargo, durante el arrendamiento el arrendatario ser&aacute; responsable de los da&ntilde;os a terceros, as&iacute; como de los da&ntilde;os a las personas o cosas que viajen dentro del veh&iacute;culo, por lo que se obliga en este acto a informar al arrendador de cualquier hecho anteriormente descrito, del robo del veh&iacute;culo o cualquier otro siniestro de manera inmediata en cuanto tenga conocimiento del hecho.<br />
                        El arrendador podr&aacute; ofrecer al arrendatario coberturas de protecci&oacute;n adicionales entre las que se pueden incluir protecci&oacute;n contra el robo del veh&iacute;culo arrendado, da&ntilde;os al veh&iacute;culo arrendado, aumento del monto de la responsabilidad civil o cualquier otra que se estime pertinente. De no aceptar ninguna de estas coberturas, el arrendatario ser&aacute; responsable frente al arrendador por el robo del veh&iacute;culo, por cualquier da&ntilde;o que sufra el veh&iacute;culo arrendado o de cualquier responsabilidad civil o penal que pudiera surgir con motivo del arrendamiento del veh&iacute;culo. El Anexo contiene las coberturas que se ofrecen al arrendatario, el monto de las mismas y las que decide aceptar el arrendatario.<br />
                        D&Eacute;CIMA TERCERA. Caso fortuito o fuerza mayor. Las partes contratantes reconocen que no existir&aacute; responsabilidad de las partes si el presente contrato se incumple por caso fortuito o fuerza mayor; sin embargo, si durante la vigencia del presente documento se origina cualquier da&ntilde;o al veh&iacute;culo por estos mismos supuestos, la arrendataria se obliga a dar aviso a la arrendadora y a las autoridades competentes tan pronto como le sea posible, sin que exceda las 48 horas posteriores a tener conocimiento del hecho. El retardo en el aviso se considerar&aacute; como incumplimiento de contrato, por lo que el arrendatario ser&aacute; responsable de indemnizar los da&ntilde;os que la arrendadora haya sufrido por causa de dicho da&ntilde;o<br />
                        D&Eacute;CIMA CUARTA. Objetos olvidados en el veh&iacute;culo arrendado. Al momento de entregar el veh&iacute;culo arrendado, ser&aacute; responsabilidad del arrendatario verificar que no existan objetos personales en el veh&iacute;culo, en caso contrario la arrendadora no ser&aacute; responsable de los objetos dejados en el veh&iacute;culo, ni del da&ntilde;o o dem&eacute;rito que pudiera ocasionarse al ser transportados dentro del mismo veh&iacute;culo.<br />
                        DECIMA QUINTA. Desperfectos mec&aacute;nicos. En caso de ocurrir alg&uacute;n desperfecto mec&aacute;nico o el&eacute;ctrico al veh&iacute;culo o la p&eacute;rdida de las llaves del mismo, el arrendatario deber&aacute; comunicar ese hecho dentro de las dos primeras horas siguientes a la arrendadora, subsistiendo en todo caso las responsabilidades a cargo de la arrendataria en caso de que el desperfecto haya sido ocasionado por alg&uacute;n acto que le sea imputable. En este caso el arrendador se obliga a sustituirle al arrendatario dicho veh&iacute;culo por otro en buen estado de uso, considerando las caracter&iacute;sticas del veh&iacute;culo arrendado, dentro de las dos horas posteriores al momento de que la arrendataria haya hecho saber su descompostura, siempre que el veh&iacute;culo se encuentre en la localidad donde fue arrendado o del domicilio del arrendador, adem&aacute;s se compromete a bonificar en el cobro por la renta, el tiempo que el arrendatario no haya podido utilizar el veh&iacute;culo por la descompostura no imputable a &eacute;l. El t&eacute;rmino expresado en este p&aacute;rrafo, podr&aacute; ampliaran, a voluntad de las partes, cuando el arrendador acredite su incumplimiento de la obligaci&oacute;n antes mencionada por causas ajenas a su voluntad.<br />
                        Para el caso de extrav&iacute;o de las llaves el arrendador le har&aacute; llegar al arrendatario, a su costa, un duplicado de las mismas dentro de las dos horas siguientes al momento de ser informado de su extrav&iacute;o, o de que se cerr&oacute; el veh&iacute;culo con las llaves dentro, siempre que el veh&iacute;culo se encuentre tambi&eacute;n dentro de la misma localidad mencionada en el p&aacute;rrafo anterior. Los cargos por la reposici&oacute;n de la llave y por el servicio de entrega del duplicado ser&aacute;n calculados a precio de mercado y cubiertos por el arrendatario.<br />
                        D&Eacute;CIMA SEXTA. Cancelaci&oacute;n del arrendamiento. El arrendatario tiene en todo momento el derecho de cancelar el arrendamiento regulado en el presente contrato, siempre y cuando la cancelaci&oacute;n se realice dentro de los cinco d&iacute;as previos al inicio de la vigencia del arrendamiento.<br />
                        En este caso, la cancelaci&oacute;n ser&aacute; sin responsabilidad alguna, y el arrendador deber&aacute; devolver &iacute;ntegramente todas las cantidades que el arrendatario le haya entregado, en un plazo de 2 d&iacute;as h&aacute;biles.<br />
                        Una vez iniciado el arrendamiento, el arrendatario podr&aacute; cancelado siempre y cuando cumpla con todas sus obligaciones de pago exigibles hasta el momento de la devoluci&oacute;n y entregue el veh&iacute;culo arrendado al arrendador en los t&eacute;rminos originalmente pactados.<br />
                        La cancelaci&oacute;n deber&aacute; realizarse en el lugar que las partes acordaron para la devoluci&oacute;n del veh&iacute;culo arrendado y que aparece en el Anexo del presente contrato, contra la devoluci&oacute;n del veh&iacute;culo y el cumplimiento de todas las obligaciones exigibles hasta el momento de la devoluci&oacute;n. En caso de que la cancelaci&oacute;n se haga en un lugar distinto al acordado, el arrendatario tambi&eacute;n pagar&aacute; los gastos que se generen con motivo de la devoluci&oacute;n del veh&iacute;culo al lugar en que se acord&oacute; su entrega.<br />
                        D&Eacute;CIMA S&Eacute;PTIMA. Causas de Rescisi&oacute;n. Las partes manifiestan su voluntad para aceptar que operar&aacute; la recisi&oacute;n ante cualquier incumplimiento de las obligaciones contenidas en este contrato.<br />
                        Cuando el arrendatario sea afectado por el incumplimiento, podr&aacute; dar por rescindido este contrato, para lo cual deber&aacute; poner el veh&iacute;culo arrendado a disposici&oacute;n del arrendador y solicitar el pago de la pena convencional correspondiente.<br />
                        Cuando el arrendador sea afectado por el incumplimiento, podr&aacute; dar por rescindido este contrato, recuperando el veh&iacute;culo en las condiciones y estado en que se localice, siendo responsable la arrendataria del pago de la pena convencional correspondiente, m&aacute;s de los gastos de recuperaci&oacute;n del veh&iacute;culo debidamente comprobados por el arrendador. El arrendador no ser&aacute; responsable de las pertenencias que se encuentren dentro del veh&iacute;culo arrendado, por lo que la arrendataria libera al arrendador de cualquier responsabilidad por pertenencias que haya dejado dentro del veh&iacute;culo arrendado.<br />
                        D&Eacute;CIMA OCTAVA. Pena Convencional. La pena convencional ser&aacute; del 20% de la cantidad total determinada como precio del arrendamiento del veh&iacute;culo.<br />
                        D&Eacute;CIMA NOVENA. Reclamaciones y quejas. Las partes acuerdan que el arrendatario podr&aacute; enviar cualquier reclamaci&oacute;n o queja del servicio al correo electr&oacute;nico del arrendador proporcionado en el Anexo del presente contrato o, en su defecto, presentarla en el domicilio descrito en el documento mencionado. En cualquier circunstancia el arrendador deber&aacute; dar respuesta al arrendatario en un plazo no mayor a dos d&iacute;as h&aacute;biles contados a partir de la recepci&oacute;n de la reclamaci&oacute;n o queja.<br />
                        VIG&Eacute;SIMA. Domicilios. Para los efectos de este contrato se se&ntilde;alan como domicilios, n&uacute;mero de tel&eacute;fono y correo electr&oacute;nico de las partes los que se citan en el Anexo de este contrato.<br />
                        VIG&Eacute;SIMA PRIMERA. Competencia Administrativa. La Procuradur&iacute;a Federal del Consumidor es competente en la v&iacute;a administrativa para resolver cualquier controversia que se suscite sobre la interpretaci&oacute;n o cumplimiento del presente contrato y su Anexo. Sin perjuicio de lo anterior, las partes se someten a la jurisdicci&oacute;n de los Tribunales competentes en la ciudad de M&eacute;rida, estado de Yucat&aacute;n, renunciando expresamente a cualquier otra jurisdicci&oacute;n que pudiera corresponderles, por raz&oacute;n de sus domicilios presentes o futuros o por cualquier otra raz&oacute;n.<br />
                        VIG&Eacute;SIMA SEGUNDA. Aviso de privacidad. El arrendador manifiesta que la informaci&oacute;n que recibe del arrendatario con motivo de la celebraci&oacute;n del presente contrato ser&aacute; empleada en t&eacute;rminos del aviso de privacidad que en este momento le entrega.<br />
                        Le&iacute;do que fue el presente contrato y su Anexo, comprendiendo las partes el alcance legal de todo lo ah&iacute; contenido, lo suscriben en su Anexo en el lugar y fecha que ah&iacute; mismo se establece. El arrendatario recibe el presente contrato por correo electr&oacute;nico a la direcci&oacute;n que ha especificado en el Anexo y, si lo solicita, tambi&eacute;n lo puede recibir impreso.<br />
                        VIG&Eacute;SIMA TERCERA. Autorizaci&oacute;n para la utilizaci&oacute;n con fines mercadot&eacute;cnicos o publicitarios. El arrendatario del servicio SI ( ) NO ( ) acepta que el arrendador ceda o transmita a terceros, con fines mercadot&eacute;cnicos o publicitarios, la informaci&oacute;n proporcionada por &eacute;l con motivo del presente contrato y SI ( ) NO ( ) acepta que el arrendador le env&iacute;e publicidad sobre bienes o servicios. Este contrato fue aprobado y registrado por la Procuradur&iacute;a Federal del Consumidor bajo el n&uacute;mero 3227-2020 de fecha 25 de agosto de 2020.<br />
                        &nbsp;</p>
                </td>
            </tr>
        </table>
        <div style="width: 100%;">
            <div style="width: 50%; text-align:center; float:right;">
                <img style="width:55%;" src="{{$contrato->firma_cliente}}" />
                <p>{{$contrato->cliente->nombre}}</p>
                <p style="font-size: 12px; text-align: center;border-top: 1px solid;letter-spacing: 7px;">
                    FIRMA DEL CLIENTE
                </p>
            </div>
        </div>

    </div>
    <div class="page-break"></div>
    <div class="check-in" style="max-width: 100%;">
        <!-- HEADER -->
        <table style="width: 100%;">
            <th>
            <td style="width: 50%;">
                <img src="{{ 'assets/img/PDF-Logo.png' }}">
            </td>
            <td style="width: 50%; vertical-align:middle">
                <p style="font-size:14px;">
                    <strong>
                        CHECK-LIST
                    </strong>
                </p>
            </td>
            </th>
        </table> <!-- END HEADER -->
        <br>
        <br>
        <div>
            <div style="width: 100%; display: table; border-bottom: solid; border-top: solid; font-size:12px;">
                <div style="display: table-row">
                    <div style="display: table-cell; text-align:center;  width:100%"> <p><b> CONDICIONES DEL CARRO AL SALIR</b></p></div>
                </div>
                <div style="display: table-row">
                    <div style="display: table-cell; text-align:center; width: 100%; "><p><b>CONDITIONAL OF CAR AT TIME RENTAL</b></p></div>
                </div>
            </div>
            <br>
            <div style="width: 100%; display: table; font-size:10px;">
                <div style="display: table-row;">
                    <div style="display: table-cell; text-align:center;  width:25%">
                        <p><span class="circulo"></span>&nbsp;<b> ABOLLADURA</b></p>
                        <p><span class="circulo"></span>&nbsp;<b> DENT</b></p>
                    </div>
                    <div style="display: table-cell; text-align:center;  width:25%">
                        <p><span class="linea"></span>&nbsp;<b> RAYON</b></p>
                        <p><span class="linea"></span>&nbsp;<b> SCRATCHES</b></p>
                    </div>
                    <div style="display: table-cell; text-align:center;  width:25%">
                        <p><span class="x">X</span>&nbsp;<b> GOLPES</b></p>
                        <p><span class="x">X</span>&nbsp;<b> BUMP</b></p>
                    </div>
                    <div style="display: table-cell; text-align:center;  width:25%">
                        <p><span class="x">^</span>&nbsp;<b> ROTURA</b></p>
                        <p><span class="x">^</span>&nbsp;<b> BROKEN</b></p>
                    </div>
                </div>
            </div>
            <div style="width: 100%; display: table;">
                <div style="display: table-row;">
                    <div style="display: table-cell;">
                        <div >
                            <img style="width: 680px; height: 500px; object-fit: contain;" src="{{$contrato->check_form_list->check_list_img}}">
                        </div>
                    </div>
                </div>
            </div>
            <table style="width: 100%; display: table; font-size:10px;">
                <tr>
                    <td style="border-bottom: solid; width:8%">{{$contrato->check_form_list->tarjeta_circulacion}}</td>
                    <td><p><span></span>&nbsp;<b> TARJETA DE CIRCULACIÓN / CAR PAPER</b></p></td>
                </tr>
                <tr>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->tapetes}}</td>
                    <p><span ></span>&nbsp;<b> TAPETES / MATS</b></p>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->llave_rueda}}</td>
                    <p><span ></span>&nbsp;<b> LLAVES DE RUEDAS / LUG</b></p>
                </tr>
                <tr>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->silla_bebes}}</td>
                    <p><span ></span>&nbsp;<b> SILLA PARA BEBES / BABY SEAT</b></p>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->limpiadores}}</td>
                    <p><span ></span>&nbsp;<b> LIMPIADORES / WIPERS</b></p>
                </tr>
                <tr>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->espejos}}</td>
                    <p><span ></span>&nbsp;<b> ESPEJOS / MIRRORS</b></p>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->antena}}</td>
                    <p><span ></span>&nbsp;<b> ANTENA </b></p>
                </tr>
                <tr>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->tapones_rueda}}</td>
                    <p><span ></span>&nbsp;<b> TAPONES DE RUEDAS / HUBS</b></p>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->navegador}}</td>
                    <p><span ></span>&nbsp;<b> NAVEGADOR / GPS</b></p>
                </tr>
                <tr>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->tapon_gas}}</td>
                    <p><span ></span>&nbsp;<b> TAPON DE GAS / GAS CAP</b></p>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->placas}}</td>
                    <p><span ></span>&nbsp;<b> PLACAS / PLATES </b></p>
                </tr>
                <tr>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->senalamientos}}</td>
                    <p><span ></span>&nbsp;<b> SEÑALAMIENTOS / NIGHT RI </b></p>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->radio}}</td>
                    <p><span ></span>&nbsp;<b> RADIO </b></p>
                </tr>
                <tr>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->gato}}</td>
                    <p><span ></span>&nbsp;<b> GATO / JACK</b></p>
                    <td style="border-bottom: solid; width:8%;">{{$contrato->check_form_list->llantas}}</td>
                    <p><span ></span>&nbsp;<b> LLANTAS / TIRES </b></p>
                </tr>
            </table>
            <br>
            <table  style="width: 100%; border: solid; border-collapse: collapse; font-size:10px;">
                <tr style="text-align: center;">
                    <td style="width: 50%; border: solid; vertical-align:baseline;">
                        <div>
                            <p>ENTREGADO POR / DELIVERED BY</p>
                        </div>
                        <div>
                            <p>{{$contrato->usuario->nombre. ' ' .$contrato->usuario->apellidos}}</p>
                        </div>

                    </td>
                    <td style="width: 50%; border: solid; vertical-align:baseline;">
                        <div>
                            <p>CLIENTE / CUSTOMER SIGNATURE</p>
                        </div>
                        <div>
                            <p>{{$contrato->cliente->nombre}}</p>
                        </div>
                        <div>
                            <img style="width:35%;" src="{{$contrato->firma_cliente}}" />
                        </div>
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <td style="width: 50%; border: solid; vertical-align:baseline;">
                        <div>
                            <p>RECIBIDO POR / RECEIVED BY</p>
                        </div>
                        <div>
                            <p></p>
                        </div>

                    </td>
                    <td style="width: 50%; border: solid; vertical-align:baseline;">
                        <div >
                            <p>OBSERVACIONES / OBSERVATIONS</p>
                        </div>
                        <div style="width: 100%;">
                            <p>{{$contrato->check_form_list->observaciones}}</p>
                        </div>

                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="page-break"></div>
    <div class="anexos" style="max-width: 100%;">

    </div>



</body>

</html>
