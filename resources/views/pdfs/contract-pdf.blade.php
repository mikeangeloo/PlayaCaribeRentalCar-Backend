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
</style>

<body style="width: 100%;">
    <div style="max-width: 100%;">
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
                    <p style="text-transform:uppercase;"><b>{{$contrato->salida->alias}}</b></p>
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
                <p style="text-transform:uppercase;"><b>{{$contrato->salida->alias}}</b></p>
                    <p style="text-transform:uppercase;">{{$contrato->salida->direccion}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->salida->colonia}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->salida->municipio}}</p>
                    <p style="text-transform:uppercase;">{{$contrato->salida->cp}}</p>
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
                        <p><b>CONFIRMACION #:</b>&nbsp;619BCFC1462</p>
                        <p><b>RES LOCAL #:</b>&nbsp;06JJW1</p>
                    </td>
                    <td style="width: 33.3333333333%; text-align: left">
                        <p><b>CANAL DE ORIGEN:</b>&nbsp;WEB</p>
                        <p><b>CÓDIGO DE TARIFA:</b>&nbsp;FCATD</p>
                    </td>
                    <td style="width: 33.3333333333%; text-align: left">
                        <p><b>NO. DE CLIENTE:</b>&nbsp;Z0KFGG</p>
                        <p><b>CONTACTO:</b>&nbsp;CZM LOCAL</p>
                    </td>
                </tr>
            </table>
            <br>

            <table style="width: 100%;">
                <tr style="font-size: 8px;">
                    <!-- INFO VEHICULO Y SEGUROS -->
                    <td style="width: 50%;">
                        <div style="width: 50%; float:left">
                            <p><b>INFORMACION DEL VEHICULO</b></p>
                        </div>
                        <br>
                        <br>
                        <div style="width: 50%; float:left">
                            <p>CLASE RESERVADA</p>
                            <p>CLASE ASIGNADA</p>
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
                            <p>C</p>
                            <p>D</p>
                            <p>VV1308</p>
                            <p>VW</p>
                            <p>VENTO</p>
                            <p>Blanco</p>
                            <p>YYP-744-D</p>
                            <p>MX93938383MT838383</p>
                            <p>55.0 LITROS</p>
                            <p>8</p>
                            <p>15018</p>
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
                                <p>MC 5470*2708 PRE-AUT: 288122 23-NOV-2022 $5,000.00</p>
                                <p>WEBBCC 1111*2708 DEPOSITO: 288122 23-NOV-2022 $1,667.41</p>
                                <p>MC 5470*2708 DEPOSITO: 288122 23-NOV-2022 $4,456.05</p>
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
                    <td style=" width: 50%; ">
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
                        <div style="width: 100%; display: table;">
                            <div style="display: table-row">
                                <div style="display: table-cell;  width:43%"></div>
                                <div style="display: table-cell; text-align:left; width:14%;"> <p><b> PRECIO.UNIT </b></p> </div>
                                <div style="display: table-cell; text-align: right; width:12%;"> <p><b>DIA</b></p> </div>
                                <div style="display: table-cell; width:24%"></div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width: 50%; text-decoration: underline;"><p><b> TARIFAS TIEMPO Y KILOMETRAJE</b></p></div>
                                <div style="display: table-cell; ">  </div>
                                <div style="display: table-cell; "></div>
                                <div style="display: table-cell;  "></div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width:45%;"> DAYS / DIAS</div>
                                <div style="display: table-cell; text-align:left; "> $750.62 </div>
                                <div style="display: table-cell; text-align:right;">X 3</div>
                                <div style="display: table-cell; width:45%;  text-align:center;">$2251.66</div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width:45%;"> DESCUENTO / DISCOUNT</div>
                                <div style="display: table-cell; text-align:left; "> 50% </div>
                                <div style="display: table-cell; text-align:right;">X 1</div>
                                <div style="display: table-cell; width:45%;  text-align:center;">-$1125.93</div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width: 50%; text-decoration: underline;"><p><b> CARGOS DE GAS</b></p></div>
                                <div style="display: table-cell; ">  </div>
                                <div style="display: table-cell; "></div>
                                <div style="display: table-cell;  "></div>
                            </div>
                        </div>
                        <br>
                        <div style="width: 100%; display: table;">
                            <div style="display: table-row">
                                <div style="display: table-cell;  width:43%"></div>
                                <div style="display: table-cell; text-align:left; width:14%;"> <p><b> PRECIO.UNIT </b></p> </div>
                                <div style="display: table-cell; text-align: right; width:12%;"> <p><b>DIA</b></p> </div>
                                <div style="display: table-cell; width:35%"></div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width: 50%; text-decoration: underline;"><p><b> PRODUCTOS ADICIONALES</b></p></div>
                                <div style="display: table-cell; ">  </div>
                                <div style="display: table-cell; "></div>
                                <div style="display: table-cell;  "></div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width:45%;"> SILLA BEBE</div>
                                <div style="display: table-cell; text-align:left; "> $300 </div>
                                <div style="display: table-cell; text-align:right;">X 3</div>
                                <div style="display: table-cell; width:45%;  text-align:center;">$900.00</div>
                            </div>
                        </div>
                        <div style="width: 90%; display: table; border: 1px solid black; padding: 10px;">
                            <div style="display: table-row">
                                <div style="display: table-cell; text-align:left; width:43%;"> <p><b>SUBTOTAL-2</b></p> </div>
                                <div style="display: table-cell; text-align:center; width:28%;"> <p><b>  </b></p> </div>
                                <div style="display: table-cell; text-align:right; width:26%;"> <p><b>$4112.93</b></p> </div>
                            </div>
                        </div>
                        <div style="width: 100%; display: table;">
                            <div style="display: table-row">
                                <div style="display: table-cell;  width:43%"></div>
                                <div style="display: table-cell; text-align:left; width:14%;"> <p><b> PRECIO.UNIT </b></p> </div>
                                <div style="display: table-cell; text-align: right; width:12%;"> <p><b>DIA</b></p> </div>
                                <div style="display: table-cell; width:24%"></div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width: 50%; text-decoration: underline;"><p><b> CUOTAS LOCALES E IMPUESTOS FEDERALES</b></p></div>
                                <div style="display: table-cell; ">  </div>
                                <div style="display: table-cell; "></div>
                                <div style="display: table-cell;  "></div>
                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; width:45%;"> I.V.A / TAX</div>
                                <div style="display: table-cell; text-align:left; ">  </div>
                                <div style="display: table-cell; text-align:right;"></div>
                                <div style="display: table-cell; width:45%;  text-align:center;">$818.15</div>
                            </div>
                        </div>
                        <div style="width: 90%; display: table; border: 1px solid black; padding: 10px;">
                            <div style="display: table-row">
                                <div style="display: table-cell; text-align:left; width:70%;"> <p><b>CARGOS ESTIMADOS TOTALES INICIALES X_______</b></p> </div>
                                <div style="display: table-cell; text-align:right; width:20%;"> <p><b>$6123.46</b></p> </div>

                            </div>
                            <div style="display: table-row">
                                <div style="display: table-cell; text-align:left; width:100%;">
                                    <p style="font-size: 6px;">*TODAS LAS CANTIDADES REFLEJADAS SON EN MONEDA NACIONAL (MXN)</p>
                                </div>
                            </div>
                        </div>
                        <div style="height: 25%;"></div>
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
                    <p>
                        <b>ELABORADO POR: APOLLO COMPANY</b> &nbsp; 23-NOV-2022 02:57 PM
                    </p>
                    <p>
                        <b> ENVIADO POR:  </b> &nbsp;  -------------
                    </p>
                </div>
                <div style="display: table-cell; text-align:center; width:55%;">
                    <img style="width:45%;" src="{{$contrato->firma_cliente}}" />
                    <p style="font-size: 12px; text-align: center;border-top: 1px solid;letter-spacing: 7px;">
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
    <div class="clausulas">
        <div style="width: 100%;">
            <table style="width: 100%;">
                <tr style="font-size: 6px;">
                    <td style="width: 50%; border-right: 1px solid black; float:left;">
                        <p style="margin-top: 25px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius, vero suscipit? Dolorem impedit, incidunt mollitia placeat odit culpa explicabo? Aliquam quas soluta perspiciatis minima sapiente numquam facilis aperiam quidem ex. Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur suscipit enim voluptate necessitatibus, blanditiis consequuntur ducimus animi laudantium voluptates maxime illum aliquam tempora nesciunt sint iure dolorum! Optio, ipsam fuga.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti vero voluptate officiis! Magni quidem veniam sit. Qui quia deserunt natus quaerat nobis odit! Porro dolorum labore, tempora aut sint soluta!
                        </p>
                        <br>
                        <div style="font-size: 8px; text-align:center" >
                            <p><b>GLOSARIO</b></p>
                        </div>
                        <br>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim accusamus atque tempora eligendi. Nihil totam delectus cumque nulla! Amet beatae voluptate dolorem fuga quo voluptates sint illo distinctio, facere quibusdam.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam quaerat saepe mollitia maiores doloribus cumque nulla obcaecati id ullam molestiae quisquam aspernatur amet quas, optio est accusantium tenetur, ratione cupiditate.
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Delectus dolor, quae quam repellat, sunt quod commodi iste ad excepturi esse reiciendis aliquam veniam recusandae aspernatur praesentium odit placeat nostrum iusto.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nostrum totam, deleniti illum natus sapiente repellendus quasi a, fugit facere, beatae iusto qui quaerat. Voluptates soluta laborum ut quam doloribus laboriosam?
                        </p>
                        <br>
                        <div style="font-size: 8px; text-align:center" >
                            <p><b>DECLARACIONES</b></p>
                        </div>
                        <br>
                        <div style="font-size: 8px; text-align:left" >
                            <p><b>PRIMERA. DECLARA EL ARRENDADOR:</b></p>
                        </div>
                        <br>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit odio quod, et qui numquam assumenda debitis, unde molestias provident at distinctio earum ut tempore ea. Laboriosam dolore molestiae nobis adipisci. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur esse nobis nisi cum quis similique, dolorum assumenda modi labore deserunt voluptate, excepturi illo voluptatum ad eveniet quod, in sed ea.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio possimus aut hic alias ab fuga neque soluta reiciendis eligendi esse dicta, praesentium, necessitatibus cupiditate. Sit deserunt officiis adipisci expedita rerum.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aut, qui! Praesentium dicta quasi nihil quod similique enim, obcaecati consequatur, ducimus repellendus officia dignissimos perspiciatis labore illo, hic facere possimus deserunt. Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi eligendi commodi corrupti culpa omnis qui, architecto dolore ex expedita temporibus tempore earum voluptates modi, quia dicta optio dignissimos at similique.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, debitis. Dignissimos voluptatum nam laboriosam incidunt similique dolorem impedit culpa sequi doloribus enim iste vitae delectus, nesciunt itaque quidem qui reprehenderit!
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, illum maiores recusandae deleniti illo ea. Rem odit labore nisi similique blanditiis nam nesciunt nemo, nihil sapiente, voluptatum atque ipsa cupiditate?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In voluptate incidunt culpa impedit, blanditiis ducimus aspernatur necessitatibus, eius porro praesentium deserunt quidem commodi aut perferendis tempora facere cum ratione fuga. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem quas unde asperiores provident qui temporibus vel. Dicta quibusdam dolorem eius neque, similique sit perspiciatis voluptatum vero quidem velit quae saepe?
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus, quidem reprehenderit voluptatibus quisquam harum doloremque excepturi. Ipsum odio corrupti sint ut exercitationem. Repellendus quibusdam dolore eius temporibus optio sit dolorem!
                        </p>
                        <br>
                        <div style="font-size: 8px; text-align:left" >
                            <p><b>SEGUNDA. DECLARA EL ARRENDATARIO:</b></p>
                        </div>
                        <br>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Deserunt possimus quae earum voluptas itaque voluptate assumenda dicta maiores nihil fugit sapiente, quidem odit enim iusto suscipit. Dignissimos doloremque enim modi.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt nesciunt laborum sapiente eius enim iure assumenda repellat error doloremque commodi molestias, praesentium hic dolorem qui ipsum nihil, maxime, magnam id.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste earum molestiae fugit maiores. Cupiditate adipisci necessitatibus dolor alias perferendis earum ex rerum commodi mollitia, minima eos qui dolorum, officiis iste?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus officia harum deleniti quibusdam tempora quidem dicta nisi, in nulla quae modi velit obcaecati facilis aliquid rerum ullam. Voluptatibus, eos perferendis.
                        </p>
                        <br>
                        <div style="font-size: 8px; text-align:center" >
                            <p><b>CLAUSULAS</b></p>
                        </div>
                        <br>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Laboriosam quam minima quibusdam ut, dolorum possimus dolor at fugiat unde non dolores. Quos voluptates perspiciatis nesciunt odit iste, inventore illum voluptatem. Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure pariatur veniam natus similique ea fuga sit earum est, qui nihil quod laboriosam provident modi, ullam quaerat repellat consequuntur voluptate ad.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Soluta minus illo eum, ratione eveniet impedit, perferendis sed ipsam officia rerum accusamus exercitationem aliquid a saepe voluptatem, earum laborum tempora fugiat. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Tenetur ipsum facilis, consectetur neque dolores labore enim quisquam fugiat libero, sapiente fuga omnis obcaecati odio eius inventore esse laborum ducimus numquam!
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quam id nobis fugit quae est, facere ducimus perferendis, ratione numquam ab expedita, facilis nisi laborum odit. Eveniet ipsam sequi voluptatem similique
                        </p>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit odio quod, et qui numquam assumenda debitis, unde molestias provident at distinctio earum ut tempore ea. Laboriosam dolore molestiae nobis adipisci. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur esse nobis nisi cum quis similique, dolorum assumenda modi labore deserunt voluptate, excepturi illo voluptatum ad eveniet quod, in sed ea.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio possimus aut hic alias ab fuga neque soluta reiciendis eligendi esse dicta, praesentium, necessitatibus cupiditate. Sit deserunt officiis adipisci expedita rerum.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aut, qui! Praesentium dicta quasi nihil quod similique enim, obcaecati consequatur, ducimus repellendus officia dignissimos perspiciatis labore illo, hic facere possimus deserunt. Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi eligendi commodi corrupti culpa omnis qui, architecto dolore ex expedita temporibus tempore earum voluptates modi, quia dicta optio dignissimos at similique.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, debitis. Dignissimos voluptatum nam laboriosam incidunt similique dolorem impedit culpa sequi doloribus enim iste vitae delectus, nesciunt itaque quidem qui reprehenderit!
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, illum maiores recusandae deleniti illo ea. Rem odit labore nisi similique blanditiis nam nesciunt nemo, nihil sapiente, voluptatum atque ipsa cupiditate?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In voluptate incidunt culpa impedit, blanditiis ducimus aspernatur necessitatibus, eius porro praesentium deserunt quidem commodi aut perferendis tempora facere cum ratione fuga. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem quas unde asperiores provident qui temporibus vel. Dicta quibusdam dolorem eius neque, similique sit perspiciatis voluptatum vero quidem velit quae saepe?
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus, quidem reprehenderit voluptatibus quisquam harum doloremque excepturi. Ipsum odio corrupti sint ut exercitationem. Repellendus quibusdam dolore eius temporibus optio sit dolorem!
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit odio quod, et qui numquam assumenda debitis, unde molestias provident at distinctio earum ut tempore ea. Laboriosam dolore molestiae nobis adipisci. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur esse nobis nisi cum quis similique, dolorum assumenda modi labore deserunt voluptate, excepturi illo voluptatum ad eveniet quod, in sed ea.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio possimus aut hic alias ab fuga neque soluta reiciendis eligendi esse dicta, praesentium, necessitatibus cupiditate. Sit deserunt officiis adipisci expedita rerum.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aut, qui! Praesentium dicta quasi nihil quod similique enim, obcaecati consequatur, ducimus repellendus officia dignissimos perspiciatis labore illo, hic facere possimus deserunt. Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi eligendi commodi corrupti culpa omnis qui, architecto dolore ex expedita temporibus tempore earum voluptates modi, quia dicta optio dignissimos at similique.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, debitis. Dignissimos voluptatum nam laboriosam incidunt similique dolorem impedit culpa sequi doloribus enim iste vitae delectus, nesciunt itaque quidem qui reprehenderit!
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, illum maiores recusandae deleniti illo ea. Rem odit labore nisi similique blanditiis nam nesciunt nemo, nihil sapiente, voluptatum atque ipsa cupiditate?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In voluptate incidunt culpa impedit, blanditiis ducimus aspernatur necessitatibus, eius porro praesentium deserunt quidem commodi aut perferendis tempora facere cum ratione fuga. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem quas unde asperiores provident qui temporibus vel. Dicta quibusdam dolorem eius neque, similique sit perspiciatis voluptatum vero quidem velit quae saepe?
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus, quidem reprehenderit voluptatibus quisquam harum doloremque excepturi. Ipsum odio corrupti sint ut exercitationem. Repellendus quibusdam dolore eius temporibus optio sit dolorem!
                        </p>
                        <p style="margin-bottom: 25px;"></p>
                    </td>
                    <td style="width: 50%; float:right;">
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit odio quod, et qui numquam assumenda debitis, unde molestias provident at distinctio earum ut tempore ea. Laboriosam dolore molestiae nobis adipisci. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur esse nobis nisi cum quis similique, dolorum assumenda modi labore deserunt voluptate, excepturi illo voluptatum ad eveniet quod, in sed ea.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio possimus aut hic alias ab fuga neque soluta reiciendis eligendi esse dicta, praesentium, necessitatibus cupiditate. Sit deserunt officiis adipisci expedita rerum.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aut, qui! Praesentium dicta quasi nihil quod similique enim, obcaecati consequatur, ducimus repellendus officia dignissimos perspiciatis labore illo, hic facere possimus deserunt. Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi eligendi commodi corrupti culpa omnis qui, architecto dolore ex expedita temporibus tempore earum voluptates modi, quia dicta optio dignissimos at similique.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, debitis. Dignissimos voluptatum nam laboriosam incidunt similique dolorem impedit culpa sequi doloribus enim iste vitae delectus, nesciunt itaque quidem qui reprehenderit!
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, illum maiores recusandae deleniti illo ea. Rem odit labore nisi similique blanditiis nam nesciunt nemo, nihil sapiente, voluptatum atque ipsa cupiditate?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In voluptate incidunt culpa impedit, blanditiis ducimus aspernatur necessitatibus, eius porro praesentium deserunt quidem commodi aut perferendis tempora facere cum ratione fuga. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem quas unde asperiores provident qui temporibus vel. Dicta quibusdam dolorem eius neque, similique sit perspiciatis voluptatum vero quidem velit quae saepe?
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus, quidem reprehenderit voluptatibus quisquam harum doloremque excepturi. Ipsum odio corrupti sint ut exercitationem. Repellendus quibusdam dolore eius temporibus optio sit dolorem!
                        </p>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit odio quod, et qui numquam assumenda debitis, unde molestias provident at distinctio earum ut tempore ea. Laboriosam dolore molestiae nobis adipisci. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur esse nobis nisi cum quis similique, dolorum assumenda modi labore deserunt voluptate, excepturi illo voluptatum ad eveniet quod, in sed ea.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio possimus aut hic alias ab fuga neque soluta reiciendis eligendi esse dicta, praesentium, necessitatibus cupiditate. Sit deserunt officiis adipisci expedita rerum.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aut, qui! Praesentium dicta quasi nihil quod similique enim, obcaecati consequatur, ducimus repellendus officia dignissimos perspiciatis labore illo, hic facere possimus deserunt. Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi eligendi commodi corrupti culpa omnis qui, architecto dolore ex expedita temporibus tempore earum voluptates modi, quia dicta optio dignissimos at similique.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, debitis. Dignissimos voluptatum nam laboriosam incidunt similique dolorem impedit culpa sequi doloribus enim iste vitae delectus, nesciunt itaque quidem qui reprehenderit!
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, illum maiores recusandae deleniti illo ea. Rem odit labore nisi similique blanditiis nam nesciunt nemo, nihil sapiente, voluptatum atque ipsa cupiditate?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In voluptate incidunt culpa impedit, blanditiis ducimus aspernatur necessitatibus, eius porro praesentium deserunt quidem commodi aut perferendis tempora facere cum ratione fuga. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem quas unde asperiores provident qui temporibus vel. Dicta quibusdam dolorem eius neque, similique sit perspiciatis voluptatum vero quidem velit quae saepe?
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus, quidem reprehenderit voluptatibus quisquam harum doloremque excepturi. Ipsum odio corrupti sint ut exercitationem. Repellendus quibusdam dolore eius temporibus optio sit dolorem!
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit odio quod, et qui numquam assumenda debitis, unde molestias provident at distinctio earum ut tempore ea. Laboriosam dolore molestiae nobis adipisci. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur esse nobis nisi cum quis similique, dolorum assumenda modi labore deserunt voluptate, excepturi illo voluptatum ad eveniet quod, in sed ea.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio possimus aut hic alias ab fuga neque soluta reiciendis eligendi esse dicta, praesentium, necessitatibus cupiditate. Sit deserunt officiis adipisci expedita rerum.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aut, qui! Praesentium dicta quasi nihil quod similique enim, obcaecati consequatur, ducimus repellendus officia dignissimos perspiciatis labore illo, hic facere possimus deserunt. Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi eligendi commodi corrupti culpa omnis qui, architecto dolore ex expedita temporibus tempore earum voluptates modi, quia dicta optio dignissimos at similique.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, debitis. Dignissimos voluptatum nam laboriosam incidunt similique dolorem impedit culpa sequi doloribus enim iste vitae delectus, nesciunt itaque quidem qui reprehenderit!
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, illum maiores recusandae deleniti illo ea. Rem odit labore nisi similique blanditiis nam nesciunt nemo, nihil sapiente, voluptatum atque ipsa cupiditate?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In voluptate incidunt culpa impedit, blanditiis ducimus aspernatur necessitatibus, eius porro praesentium deserunt quidem commodi aut perferendis tempora facere cum ratione fuga. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem quas unde asperiores provident qui temporibus vel. Dicta quibusdam dolorem eius neque, similique sit perspiciatis voluptatum vero quidem velit quae saepe?
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus, quidem reprehenderit voluptatibus quisquam harum doloremque excepturi. Ipsum odio corrupti sint ut exercitationem. Repellendus quibusdam dolore eius temporibus optio sit dolorem!
                        </p>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Laboriosam quam minima quibusdam ut, dolorum possimus dolor at fugiat unde non dolores. Quos voluptates perspiciatis nesciunt odit iste, inventore illum voluptatem. Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure pariatur veniam natus similique ea fuga sit earum est, qui nihil quod laboriosam provident modi, ullam quaerat repellat consequuntur voluptate ad.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Soluta minus illo eum, ratione eveniet impedit, perferendis sed ipsam officia rerum accusamus exercitationem aliquid a saepe voluptatem, earum laborum tempora fugiat. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Tenetur ipsum facilis, consectetur neque dolores labore enim quisquam fugiat libero, sapiente fuga omnis obcaecati odio eius inventore esse laborum ducimus numquam!
                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quam id nobis fugit quae est, facere ducimus perferendis, ratione numquam ab expedita, facilis nisi laborum odit. Eveniet ipsam sequi voluptatem similique
                        </p>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit odio quod, et qui numquam assumenda debitis, unde molestias provident at distinctio earum ut tempore ea. Laboriosam dolore molestiae nobis adipisci. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur esse nobis nisi cum quis similique, dolorum assumenda modi labore deserunt voluptate, excepturi illo voluptatum ad eveniet quod, in sed ea.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio possimus aut hic alias ab fuga neque soluta reiciendis eligendi esse dicta, praesentium, necessitatibus cupiditate. Sit deserunt officiis adipisci expedita rerum.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aut, qui! Praesentium dicta quasi nihil quod similique enim, obcaecati consequatur, ducimus repellendus officia dignissimos perspiciatis labore illo, hic facere possimus deserunt. Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi eligendi commodi corrupti culpa omnis qui, architecto dolore ex expedita temporibus tempore earum voluptates modi, quia dicta optio dignissimos at similique.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, debitis. Dignissimos voluptatum nam laboriosam incidunt similique dolorem impedit culpa sequi doloribus enim iste vitae delectus, nesciunt itaque quidem qui reprehenderit!
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, illum maiores recusandae deleniti illo ea. Rem odit labore nisi similique blanditiis nam nesciunt nemo, nihil sapiente, voluptatum atque ipsa cupiditate?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In voluptate incidunt culpa impedit, blanditiis ducimus aspernatur necessitatibus, eius porro praesentium deserunt quidem commodi aut perferendis tempora facere cum ratione fuga. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem quas unde asperiores provident qui temporibus vel. Dicta quibusdam dolorem eius neque, similique sit perspiciatis voluptatum vero quidem velit quae saepe?
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus, quidem reprehenderit voluptatibus quisquam harum doloremque excepturi. Ipsum odio corrupti sint ut exercitationem. Repellendus quibusdam dolore eius temporibus optio sit dolorem!
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit odio quod, et qui numquam assumenda debitis, unde molestias provident at distinctio earum ut tempore ea. Laboriosam dolore molestiae nobis adipisci. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur esse nobis nisi cum quis similique, dolorum assumenda modi labore deserunt voluptate, excepturi illo voluptatum ad eveniet quod, in sed ea.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio possimus aut hic alias ab fuga neque soluta reiciendis eligendi esse dicta, praesentium, necessitatibus cupiditate. Sit deserunt officiis adipisci expedita rerum.
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aut, qui! Praesentium dicta quasi nihil quod similique enim, obcaecati consequatur, ducimus repellendus officia dignissimos perspiciatis labore illo, hic facere possimus deserunt. Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi eligendi commodi corrupti culpa omnis qui, architecto dolore ex expedita temporibus tempore earum voluptates modi, quia dicta optio dignissimos at similique.
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, debitis. Dignissimos voluptatum nam laboriosam incidunt similique dolorem impedit culpa sequi doloribus enim iste vitae delectus, nesciunt itaque quidem qui reprehenderit!
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, illum maiores recusandae deleniti illo ea. Rem odit labore nisi similique blanditiis nam nesciunt nemo, nihil sapiente, voluptatum atque ipsa cupiditate?
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. In voluptate incidunt culpa impedit, blanditiis ducimus aspernatur necessitatibus, eius porro praesentium deserunt quidem commodi aut perferendis tempora facere cum ratione fuga. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Exercitationem quas unde asperiores provident qui temporibus vel. Dicta quibusdam dolorem eius neque, similique sit perspiciatis voluptatum vero quidem velit quae saepe?
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus, quidem reprehenderit voluptatibus quisquam harum doloremque excepturi. Ipsum odio corrupti sint ut exercitationem. Repellendus quibusdam dolore eius temporibus optio sit dolorem!
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 100%; margin-top:100px;">
            <div style="width: 50%; float:right;">
                <div></div>
                <p style="font-size: 1rem; text-align: center;border-top: 1px solid;letter-spacing: 7px;">
                    FIRMA DEL CLIENTE
                </p>
            </div>
        </div>
        <div class="page-break"></div>
        <div class="check-in">
            <!-- HEADER -->
            <table style="width: 100%;">
                <th>
                <td style="width: 50%;">
                    <img src="{{ 'assets/img/PDF-Logo.png' }}">
                </td>
                <td style="width: 50%; vertical-align:middle">
                    <p style="font-size:14px;">
                        <strong>
                            CHECK-IN
                        </strong>
                    </p>
                </td>
                </th>
            </table> <!-- END HEADER -->
            <br>
            <br>
            <div>
                <p style="font-size: 50px; text-align:center"> EN CONSTRUCCION</p>
            </div>
        </div>
    </div>

</body>

</html>
