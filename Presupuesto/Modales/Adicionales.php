 <!-- VENTANA MODAL MAQUINARIA-->
 <div class="modal fade" id="ModalAdicional" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog" id="draggable" role="document" >
        <div class="modal-content" >
        <div class="modal-header">
            <h5>ADICIONALES</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="search" class="form-control form-control-sm " placeholder="Buscar">
            <table class="table table-sm table-striped table-hover txt-11">
                <thead class="table-primary">
                    <th></th>
                    <th>SERVICIOS ADICIONALES</th>
                    <th>ORIGEN</th>
                    <th>UNIDAD</th>
                    <th>P.UNIT</th>     
                </thead>
                <tbody>
                    <?php
                        for($i = 0; $i <= 50; $i++){
                    ?>
                    <tr style="padding:0px;">
                        <td><button title="Agregar a maquinaria" data-toggle="tooltip" ><small class="fa fa-arrow-left" style="font-size:15px;color:green"></small></button></td>
                        <td>SERVICIO DE ALIMENTOS</td>
                        <td ><select>
                            <option>NACIONAL</option>
                            <option>IMPORTACIÓN</option>
                        </select></td>
                        <td ><select>
                            <option>SERVICIO</option>
                            <option>PZA</option>
                        </select></td>
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