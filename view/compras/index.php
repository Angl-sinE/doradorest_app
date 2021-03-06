﻿<?php
date_default_timezone_set('America/Costa_Rica');
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
$fecha = date("d-m-Y");
$fechaa = date("m-Y");
?>

<input type="hidden" id="moneda" value="<?php echo $_SESSION["moneda"]; ?>"/>
<input type="hidden" id="m" value="<?php echo $_GET['m']; ?>"/>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2><i class="fa fa-shopping-cart"></i> <a href="?c=Compra" class="a-c">Compras</a></h2>
        <ol class="breadcrumb">
            <li class="active">
                <strong>Compras</strong>
            </li>
            <li>
                Lista
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="ibox">
        <div class="ibox-title">
            <div class="ibox-title-buttons pull-right">
                <a class="btn btn-primary" href="?c=Compra&a=Nuevo"> <i class="fa fa-plus-circle"></i> Nueva Compra</a>
            </div>
            <h5><strong><i class="fa fa-list-ul"></i> Lista de Compras</strong></h5>
        </div>
        <div class="ibox-content" style="position: relative; min-height: 30px;">
            <div class="row">
                <form method="post" enctype="multipart/form-data" target="_blank" action="#">
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-r text-center" name="start" id="start" value="<?php echo '01-'.$fechaa; ?>"/>
                                        <span class="input-group-addon">al</span>
                                        <input type="text" class="form-control bg-r text-center" name="end" id="end" value="<?php echo $fecha; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <select name="cod_prov" id="cod_prov" class="selectpicker show-tick form-control"  data-live-search="true" autocomplete="off">
                                <option value="%" active>Todos los proveedores</option>
                                <?php foreach($this->model->Proveedores() as $r): ?>
                                    <option value="<?php echo $r->id_prov; ?>"><?php echo $r->ruc.' - '.$r->razon_social; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select name="tipo_doc" id="tipo_doc" class="selectpicker show-tick form-control" data-live-search="true" autocomplete="off">
                                <option value="%" active>Todos los comprobantes</option>
                                <option value="1">BOLETA</option>
                                <option value="2">FACTURA</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="punteo">
                <div class="row">
                    <div class="col-lg-4">
                        <h5 class="no-margins"><strong>Subtotal</strong></h5>
                        <h1 class="no-margins"><strong id="subt_c"></strong></h1>
                    </div>
                    <div class="col-lg-4">
                        <h5 class="no-margins"><strong>IGV</strong></h5>
                        <h1 class="no-margins"><strong id="igv_c"></strong></h1>
                    </div>
                    <div class="col-lg-4">
                        <h5 class="no-margins"><strong>Total</strong></h5>
                        <h1 class="no-margins"><strong id="total_c"></strong></h1>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table" class="table table-hover table-condensed table-striped" width="100%">
                    <thead>
                    <tr>
                        <th width="10%">Fecha</th>
                        <th>Documento</th>
                        <th>Serie - Nro.</th>
                        <th style="width: 30%">Proveedor</th>
                        <th class="text-right">Total</th>
                        <th>Forma de pago</th>
                        <th class="text-center">Estado</th>
                        <th width="10%" class="text-center"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="mdl-detalle-compra" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h5 class="modal-title" style="font-size: 18px">Detalle</h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>C&oacute;digo</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>P.U.</th>
                                <th class="text-right">Importe</th>
                            </tr>
                        </thead>
                        <tbody id="lista_productos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="mdl-eliminar-compra" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated bounceInRight">
        <form id="form_ec" method="post" enctype="multipart/form-data" action="?c=Compra&a=AnularCompra">
        <input type="hidden" name="cod_compra" id="cod_compra">
            <div class="modal-header mh-p">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <i class="fa fa-ban modal-icon"></i>
            </div>
            <div class="modal-body">
                <center>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-transparent text-center p-md">
                            <i class="fa fa-warning fa-3x text-warning"></i>
                            <h3 class="m-t-none m-b-sm text-warning">Advertencia</h3>
                            <p>Al anular la compra se descontar&aacute; las cantidades del almac&eacute;n.</p>
                        </div>
                    </div>
                </div>
                <h3>¿Desea anular esta compra?</h3>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Aceptar</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script src="assets/scripts/compras/func-compras.js"></script>