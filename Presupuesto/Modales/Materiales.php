<?php
    include_once('../../controlador/Material.php');
    $oMat = new Material();//Objeto material

    $sucursal = 1;
?> 
 <!-- VENTANA MODAL MATERIALES-->
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg txt-12" id="draggable" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5>MATERIALES</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="search" class="form-control form-control-sm" placeholder="Buscar">
            <table class="table table-sm table-striped table-hover">
                <thead class="table-primary">
                    <th></th>
                    <th>MATERIALES/CONSUMIBLES</th>
                    <th>ORIGEN</th>
                    <th>UNIDAD</th>
                    <th>P/UNIT</th>     
                </thead>
                <tbody>
                    <?php
                        foreach($oMat->GetDataAll($sucursal) as $material) {
                    ?>
                    <tr>
                        <td><button title="Agregar a materiales" data-toggle="tooltip" ><small class="fa fa-arrow-left" style="font-size:15px;color:green"></small></button></td>
                        <td><?php echo $material['nombre']; ?></td>
                        <td ><select>
                            <?php $sNa = $material['origen'] == 'NACIONAL' ? 'selected' : '';
                                  $sIm = $material['origen'] == 'IMPORTACIÓN' ? 'selected' : '';
                              ?>
                            <option>NACIONAL</option>
                            <option>IMPORTACIÓN</option>
                        </select></td>
                        <td>
                        <select>
                            <option>PZA</option>
                            <option>LITRO</option>
                        </select>
                        </td>
                        <td>
                            <p class="badge badge-warning txt-12">$1,000</p>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        </div>
    </div>
    </div>
    <!-- FIN DE LA MODAL -->