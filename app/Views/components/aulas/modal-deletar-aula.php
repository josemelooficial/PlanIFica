<div class="modal fade" id="modal-deletar-aula" tabindex="-1" aria-labelledby="ModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Confirmação necessária</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="deletarAula" class="forms-sample" method="post" action='<?php echo base_url('sys/aulas/deletar'); ?>'>
                <?php echo csrf_field() ?>
                <input type="hidden" id="deletar-id" name="id" />
                <div class="modal-body text-break">
                    Confirma a exclusão da aula de
                    <strong id='deletar-disciplina'></strong>,
                    da turma <strong id='deletar-turma'></strong>,
                    do curso <strong id='deletar-curso'></strong>?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger me-2">Excluir</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Evento quando o modal de exclusão é aberto
        $('#modal-deletar-aula').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            var id = button.data('id');
            var curso = button.data('curso');
            var turma = button.data('turma');
            var disciplina = button.data('disciplina');

            var modal = $(this);
            modal.find('#deletar-id').val(id);
            modal.find('#deletar-curso').text(curso);
            modal.find('#deletar-turma').text(turma);
            modal.find('#deletar-disciplina').text(disciplina);
        });

        // Evento de submit do formulário de exclusão individual
        $("#deletarAula").on("submit", function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let data = form.serialize();

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function(response) {
                    if (response == "ok") {
                        // Fechar o modal
                        $('#modal-deletar-aula').modal('hide');

                        // Mensagem de sucesso
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Aula excluída com sucesso!',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#46c35f',
                            position: 'top-center'
                        });

                        if (typeof table !== 'undefined') {
                            table.ajax.reload();
                        }
                    } else {
                        if (response && response !== "ok") {
                            $.toast({
                                heading: 'Erro',
                                text: response,
                                showHideTransition: 'fade',
                                icon: 'error',
                                loaderBg: '#dc3545',
                                position: 'top-center'
                            });
                        } else {
                            alert("Erro ao excluir a aula.");
                        }
                    }
                },
                error: function() {
                    alert("Erro inesperado ao excluir a aula.");
                }
            });
        });
    });
</script>