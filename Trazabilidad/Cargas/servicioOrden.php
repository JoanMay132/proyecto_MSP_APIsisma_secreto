<?php
    include_once '../../controlador/conexion.php';
    include_once "../../controlador/Entrega.php";
    include_once "../../controlador/Orden.php";
    include_once "../../controlador/Unidad.php";
    
    
    $row = 0; //Conteo de filas
    $oEntrega = new Entrega();
    $oOrden = new Orden();

    $ot = base64_decode($_POST['id']);
    $obunidad = new Unidad();

    foreach ($obunidad->GetDataAll() as $value) {
        $data[] = array("pkunidad" =>$value['pkunidad'],"nombre" =>$value['nombre']);
    }

    //Si se manda un parametro para cambiar la ot, se edita
    if(isset($_POST['entrega']) && $_POST['entrega'] != ''){
        $list = array();
        $ent =(int) base64_decode($_POST['entrega']);
        foreach( $oEntrega->GetDataAllServ($ent) as $rows){
            array_push($list,$rows['pkserventrega']);
        } 

    }

    foreach ($oOrden->GetDataAllServ($ot)as $value) {
 ?>

<tr style="max-height: 80px;">
   <?php if(isset($_POST['entrega']) && $_POST['entrega'] != ''){ ?> <input type="hidden" name="pkserv[]" value="<?php echo base64_encode(@$list[$row]); ?>"> <?php }?>
    <td valign="top"><input name="pda[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm " value="<?php echo $value["pda"]; ?>" autocomplete="off"></td>
    <td valign="top"><input name="cant[]" type="number" min="0.00" step="0.01" class="form-control form-control-sm " value="<?php echo $value["cantidad"]; ?>" autocomplete="off"></td>
    <td valign="top">
        <select name="unidad[]" class="form-control form-control-sm ">
            <option value=""></option>
            <?php foreach($data as $unidad){ 
                    $sel = $unidad['pkunidad'] == $value['unidad'] ? 'selected' : '';
                ?>
                <option <?php echo $sel; ?> value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
            <?php } ?>
        </select>
    </td>
    <td valign="top"><textarea onclick="menu(this); return false;" oninput="autoResize(this,0);" spellcheck="false" id="descLoad-<?php echo $row;?>" name="descripcion[]" class="form-control cajas-texto" autocomplete="off" style="resize:none;" onfocus="mostrarScroll('descLoad-<?php echo $row;?>')" onblur="ocultarScroll('descLoad-<?php echo $row;?>')"><?php echo $value['descripcion']; ?></textarea></td>

</tr>

<?php $row++; } if($row == 0){ ?>
<tr style="max-height: 80px;"  >
   <?php if(isset($_POST['entrega']) && $_POST['entrega'] != ''){ ?> <input type="hidden" name="pkserv[]" value="<?php echo base64_encode(@$list[$row]); ?>"> <?php }?>
    <td valign="top"><input name="pda[]" type="number" min="1.0" step="0.01" class="form-control form-control-sm "  autocomplete="off"></td>
    <td valign="top"><input name="cant[]" type="number" min="1.0" step="0.01" class="form-control form-control-sm "  autocomplete="off"></td>
    <td valign="top">
        <select name="unidad[]" class="form-control form-control-sm ">
            <option value=""></option>
            <?php foreach($data as $unidad){ ?>
                <option value=" <?php echo $unidad["pkunidad"]; ?>"><?php echo $unidad["nombre"]; ?></option>
            <?php } ?>
        </select>
    </td>
    <td valign="top"><textarea onclick="menu(this); return false;" oninput="autoResize(this,0);" spellcheck="false" id="descLoad-<?php echo $row;?>" name="descripcion[]" class="form-control cajas-texto" autocomplete="off" style="resize:none;" onfocus="mostrarScroll('descLoad-<?php echo $row;?>')" onblur="ocultarScroll('descLoad-<?php echo $row;?>')"></textarea></td>

</tr>
<?php } ?>