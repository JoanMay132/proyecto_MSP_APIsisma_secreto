<?php
    include_once '../../controlador/conexion.php';
    include_once "../../controlador/Revpreeliminar.php";
    include_once "../../controlador/Cotizacion.php";
    include_once "../../controlador/Unidad.php";
    
    
    $row = 0; //Conteo de filas
    $oRev = new Revpreeliminar();
    $oCot = new Cotizacion();

    $rev = base64_decode($_POST['id']);
    $obunidad = new Unidad();

    foreach ($obunidad->GetDataAll() as $value) {
        $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
    }
    $tipo = array ("TITULO","CONVENCIONAL","MANUFACTURA API","MAQUINADO DE CONEXIONES","MAQUINADO DE SELLO","REVESTIMIENTO","MAQUINADO ACERO","RECTIFICADO DE CARBURO","RENTA","SOLDADURA");

    //Si se manda un parametro para cambiar la cot, se edita
    if(isset($_POST['cot']) && $_POST['cot'] != ''){
        $list = array(); //Lista de servicios de la orden
        $cot =(int) base64_decode($_POST['cot']);
        foreach( $oCot->GetDataAllServ($cot) as $rows){
            array_push($list,$rows['pkservcot']);
        } 

    }


    foreach ($oRev->GetDataAllServ($rev)as $value) {
 
 ?>

<tr style="max-height: 80px;" ondblclick='addServcot(<?php echo json_encode($data); ?>,undefined,<?php echo json_encode($tipo); ?>)' >
<?php if(isset($_POST['cot']) && !empty($_POST['cot'])){ ?> <input type="hidden" name="pkservcotizacion[]" value="<?php echo base64_encode(@$list[$row]); ?>"> <?php }?>
    <input type="hidden" name="fkcatserv[]" id="fkcatservLoad-<?php echo $row;?>">
    <td valign="top" style="border-left:2px solid red;"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm" value="<?php echo $value["pda"]; ?>" autocomplete="off"></td>
    <td valign="top"><input name="cant[]" id="cantidadLoad-<?php echo $row;?>" data-pre="cantidad" type="number" min="0.00" step="0.01" class="form-control form-control-sm" value="<?php echo $value["cantidad"]; ?>" onblur="Subtotal(this,true);totales();" autocomplete="off"></td>
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
    <td valign="top"><textarea onclick="menu(this); return false;" oninput="autoResize(this,0);" spellcheck="false" id="descLoad-<?php echo $row;?>" name="descripcion[]" class="form-control cajas-texto" autocomplete="off" style="resize:none;height:30px;" onfocus="mostrarScroll('descLoad-<?php echo $row;?>')" onblur="ocultarScroll('descLoad-<?php echo $row;?>')"  data-servicio="servicios?row=<?php echo $row;?>&newLoad=true"><?php echo $value['descripcion']; ?></textarea></td>
    <td valign="top" >
        <select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">
                <option value=""></option>
                                        <?php   
                                                foreach ($tipo as $tip) {
                                                    $selTipo = $value['tipotrabajo'] == $tip ? 'selected' : '';
                                                    echo "<option ".$selTipo." value='".$tip."'>".$tip."</option>";
                                                }
                                        ?>
            
        </select>
    </td>
    <?php $subtotal = ($value["cantidad"]*$value["costo"]);  ?>
    <td valign="top"><input onchange="moneda(this); Subtotal(this,true); totales();" id="costoLoad-<?php echo $row;?>" name="costo[]" type="text" data-pre="costo"  class="form-control form-control-sm" autocomplete="off" value="$<?php echo number_format($value["costo"], 2, '.', ','); ?>" ></td>

    <td valign="top"><input name="subtotal[]" type="text" id="subtotalLoad-<?php echo $row;?>" data-pre="subtotal" class="form-control form-control-sm subtotal" autocomplete="off" value="$<?php echo number_format($subtotal, 2, '.', ','); ?>" ></td>

    <td valign="top"><textarea id="" name="clave[]" class="form-control" autocomplete="off" style="resize:none;height:30px;" oninput="autoResize(this);" spellcheck="false"></textarea></td>

    <td valign="top"><input id="itemLoad-<?php echo $row;?>"  name="item[]" type="text" value="<?php echo $value["item"]; ?>"  class="form-control form-control-sm" autocomplete="off"></td>
</tr>

<?php $row++; } ?>

