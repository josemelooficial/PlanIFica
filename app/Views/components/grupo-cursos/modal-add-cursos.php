<?php if (!empty($grupos)): ?>
    <?php foreach ($grupos as $grupo): ?>
        <div class="modal fade" id="modal-add-cursos-gp-<?= $grupo['id'] ?>" tabindex="-1" aria-labelledby="ModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Adicionar Cursos ao Grupo: <?= esc($grupo['nome']) ?></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="addCursosGrupo-<?= $grupo['id'] ?>" class="forms-sample" method="post" action='<?php echo base_url('sys/curso/adicionar-curso-grupo'); ?>'>
                        <div class="modal-body">
                            <?php echo csrf_field(); ?>
                            <!-- ID do grupo sendo passado para o formulário -->
                            <input type="hidden" name="grupo_de_cursos_id" value="<?= $grupo['id']; ?>">

                            <div class="form-group">
                                <label>Selecione os Cursos</label>
                                <select id="select-curso-grupo-<?= $grupo['id'] ?>" class="js-example-basic-multiple" name="cursos[]" multiple="multiple" style="width:100%;">
                                    <?php foreach ($cursos as $curso): ?>
                                        <?php
                                        $isAssigned = in_array($curso['id'], array_column($grupo['cursos'], 'curso_id'));
                                        ?>
                                        <option value="<?= esc($curso['id']) ?>" <?= $isAssigned ? 'selected' : '' ?>>
                                            <?= esc($curso['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary me-2">Salvar</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<script>
    $(document).ready(function() {
        // Para cada grupo
        <?php foreach ($grupos as $grupo): ?>
            $('#select-curso-grupo-<?= $grupo['id'] ?>').select2({
                dropdownParent: $('#modal-add-cursos-gp-<?= $grupo['id'] ?>'),
                width: '100%'
            });
        <?php endforeach; ?>

        $('form[id^="addCursosGrupo-"]').on('submit', function(e) {
            const selectElement = $(this).find('select[name="cursos[]"]');
            const selectedOptions = selectElement.select2('data');

            selectedOptions.forEach(function(option) {
                const optionElement = selectElement.find('option[value="' + option.id + '"]');
                optionElement.removeAttr('selected');
            });
        });
    });
</script>