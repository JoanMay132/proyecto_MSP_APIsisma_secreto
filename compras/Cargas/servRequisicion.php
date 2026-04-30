<?php
    include_once '../../controlador/conexion.php';
    include_once "../../controlador/Requisicion.php";
    include_once "../../controlador/Unidad.php";

    $row = 0; //Conteo de filas
    $oReq = new Requisicion();

    $req = base64_decode($_POST['id']);
    $obunidad = new Unidad();

    foreach ($obunidad->GetDataAll() as $value) {
        $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
    }


    foreach ($oReq->GetDataAllServ($req)as $value) {
 
 ?>
<tr style="max-height: 80px;" ondblclick='addServOcompra(<?php echo json_encode($data); ?>)' id="servcarga-<?php echo $row; ?>">
                            <td valign="top"><input name="pda[]" type="number" value="<?php echo $value['pda']; ?>" min="0.00" step="0.01" class="form-control form-control-sm text-center"  autocomplete="off" inputmode="numeric"></td>
                            <td valign="top">
                                <select name="unidad[]" class="form-control form-control-sm">
                                    <option value=""></option>
                                    <?php foreach($data as $unidad){ 
                                        $sel = $unidad['pkunidad'] == $value['unidad'] ? 'selected' : '';
                                        ?>
                                        <option <?php echo $sel; ?> value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td valign="top"><input type="number" name="cant[]" value="<?php echo $value['cantidad']; ?>" min="0.00" step="0.01" onblur="Subtotal(this); totales();" data-pre="cantidad" class="form-control form-control-sm text-center"  autocomplete="off"></td>                            
                            <td valign="top"><input type="text" name="punit[]"  data-pre='costo' onblur="Subtotal(this); totales();" onchange="window.moneda(this);"  class="form-control form-control-sm text-center"  autocomplete="off"></td>
                            <td valign="top"><input type="text" name="importe[]"  data-pre='subtotal'  onchange="window.moneda(this);" class="form-control form-control-sm text-center subtotal"  autocomplete="off"></td>
                            <td valign="top"><textarea name="descripcion[]" id="descripcioncarga-<?php echo $row; ?>" class="form-control form-control-sm cajas-texto" autocomplete="off" style="resize:none;height:30px;"  onclick="menu(this); return false;"  oninput="autoResize(this);" spellcheck="false" onfocus="mostrarScroll('descripcioncarga-<?php echo $row; ?>')" onblur="ocultarScroll('descripcioncarga-<?php echo $row; ?>')"><?php echo $value['descripcion']; ?></textarea></td>
                        </tr>
<?php $row++; } ?>

