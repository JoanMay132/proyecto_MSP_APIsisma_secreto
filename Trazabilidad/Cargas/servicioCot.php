<?php
    include_once '../../controlador/conexion.php';
    include_once "../../controlador/Cotizacion.php";
    include_once "../../controlador/Orden.php";
    include_once "../../controlador/Unidad.php";
    
    
    $row = 0; //Conteo de filas
    $oCot = new Cotizacion();
    $oOrden = new Orden();

    $cot = base64_decode($_POST['id']);
    $obunidad = new Unidad();

    foreach ($obunidad->GetDataAll() as $value) {
        $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
    }
    $tipo = array ("TITULO","CONVENCIONAL","MANUFACTURA API","MAQUINADO DE CONEXIONES","MAQUINADO DE SELLO","REVESTIMIENTO","MAQUINADO ACERO","RECTIFICADO DE CARBURO","RENTA","SOLDADURA");


    //Si se manda un parametro para cambiar la ot, se edita
    if(isset($_POST['ot']) && $_POST['ot'] != ''){
        $list = array();
        $ot =(int) base64_decode($_POST['ot']);
        foreach( $oOrden->GetDataAllServ($ot) as $rows){
            array_push($list,$rows['pkservorden']);
        } 

    }

    foreach ($oCot->GetDataAllServ($cot)as $value) {
 ?>

<tr style="max-height: 80px;" ondblclick='addServot(<?php echo json_encode($data); ?>,<?php echo json_encode($tipo); ?>)' >
   <?php if(isset($_POST['ot']) && !empty($_POST['ot'])){ ?> <input type="hidden" name="pkserv[]" value="<?php echo base64_encode(@$list[$row]); ?>"> <?php }?>
    <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center" value="<?php echo $value["pda"]; ?>" autocomplete="off"></td>
    <td valign="top"><input name="cant[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center" value="<?php echo $value["cant"]; ?>" onblur="Subtotal(this,true);totales();" autocomplete="off"></td>
    <td valign="top">
        <select name="unidad[]" class="form-control form-control-sm text-center">
            <option value=""></option>
            <?php foreach($data as $unidad){ 
                    $sel = $unidad['pkunidad'] == $value['fkunidad'] ? 'selected' : '';
                ?>
                <option <?php echo $sel; ?> value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
            <?php } ?>
        </select>
    </td>
    <td valign="top"><textarea onclick="menu(this); return false;" oninput="autoResize(this,0);" spellcheck="false" id="descLoad-<?php echo $row;?>" name="descripcion[]" class="form-control cajas-texto" autocomplete="off" style="resize:none;" onfocus="mostrarScroll('descLoad-<?php echo $row;?>')" onblur="ocultarScroll('descLoad-<?php echo $row;?>')"><?php echo $value['descripcion']; ?></textarea></td>
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

    <!-- <td valign="top"><input type="text" name="costo[]"  class="form-control form-control-sm text-center" autocomplete="off" ></td>
    <td valign="top"><input name="orden[]" type="text" class="form-control form-control-sm text-center" autocomplete="off"></td> -->
    <td valign="top"><textarea id="" name="dibujo[]" class="form-control text-center" autocomplete="off" oninput="autoResize(this);" spellcheck="false" style="resize:none;height:30px;"></textarea></td>
</tr>

<?php $row++; } if($row == 0){ ?>
<tr style="max-height: 80px;" ondblclick='addServot(<?php echo json_encode($data); ?>,<?php echo json_encode($tipo); ?>)' >
   <?php if(isset($_POST['ot']) && !empty($_POST['ot'])){ ?> <input type="hidden" name="pkserv[]" value="<?php echo base64_encode(@$list[$row]); ?>"> <?php }?>
    <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center" autocomplete="off"></td>
    <td valign="top"><input name="cant[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm text-center"  onblur="Subtotal(this,true);totales();" autocomplete="off"></td>
    <td valign="top">
        <select name="unidad[]" class="form-control form-control-sm text-center">
            <option value=""></option>
            <?php foreach($data as $unidad){ 
                ?>
                <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
            <?php } ?>
        </select>
    </td>
    <td valign="top"><textarea onclick="menu(this); return false;" oninput="autoResize(this,0);" spellcheck="false" id="descLoad-<?php echo $row;?>" name="descripcion[]" class="form-control cajas-texto" autocomplete="off" style="resize:none;" onfocus="mostrarScroll('descLoad-<?php echo $row;?>')" onblur="ocultarScroll('descLoad-<?php echo $row;?>')"></textarea></td>
    <td valign="top" >
        <select name="ttrabajo[]" class="form-control form-control-sm" style="text-align: justify;white-space:wrap;padding:0px">
                <option value=""></option>
                                        <?php   
                                                foreach ($tipo as $tip) {
                                                    
                                                    echo "<option  value='".$tip."'>".$tip."</option>";
                                                }
                                        ?>
            
        </select>
    </td>

    <!-- <td valign="top"><input type="text" name="costo[]"  class="form-control form-control-sm text-center" autocomplete="off" ></td>
    <td valign="top"><input name="orden[]" type="text" class="form-control form-control-sm text-center" autocomplete="off"></td> -->
    <td valign="top"><textarea id="" name="dibujo[]" class="form-control text-center" autocomplete="off" oninput="autoResize(this);" spellcheck="false" style="resize:none;height:30px;"></textarea></td>
</tr>
<?php }?>