<div class="modal fade" id="modal-deletar-aulas" tabindex="-1" aria-labelledby="ModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Confirmação necessária</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="deletarAulas" class="forms-sample" method="post" action='<?php echo base_url('sys/aulas/deletarMulti'); ?>'>
                <?php echo csrf_field() ?>
                <div class="modal-body text-break">
                    Confirma a exclusão de <span id="quantidade-aulas-selecionadas" class="fw-bold">0</span> aula(s) selecionada(s)?
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
        // Atualiza a contagem quando o modal é aberto
        $('#modal-deletar-aulas').on('show.bs.modal', function() {
            updateSelectedCount();
        });

        // Função para atualizar a contagem de aulas selecionadas
        function updateSelectedCount() {
            const selectedCount = $('input[name="selecionados[]"]:checked').length;
            $('#quantidade-aulas-selecionadas').text(selectedCount);

            // Atualiza também o texto no botão de submit
            const submitBtn = $('#deletarAulas').find('button[type="submit"]');
            if (selectedCount > 0) {
                submitBtn.prop('disabled', false);
                submitBtn.text(`Excluir ${selectedCount} aula(s)`);
            } else {
                submitBtn.prop('disabled', true);
                submitBtn.text('Excluir');
            }
        }

        // Atualiza a contagem quando qualquer checkbox é alterado
        $(document).on('change', 'input[name="selecionados[]"]', function() {
            updateSelectedCount();
        });

        // Submit do formulário
        $('#deletarAulas').submit(function(e) {
            e.preventDefault();

            const selectedCount = $('input[name="selecionados[]"]:checked').length;
            if (selectedCount === 0) {
                $.toast({
                    heading: 'Aviso',
                    text: 'Nenhuma aula selecionada para exclusão',
                    showHideTransition: 'fade',
                    icon: 'warning',
                    loaderBg: '#ffc107',
                    position: 'top-center'
                });
                return;
            }

            const form = $(this);
            const url = form.attr('action');
            const data = [];

            $('input[name="selecionados[]"]:checked').each(function() {
                data.push($(this).val());
            });

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    selecionados: data
                },
                beforeSend: function() {
                    // Mostrar loading
                    $('#modal-deletar-aulas').find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processando...');
                },
                success: function(response) {
                    if (response == "ok") {
                        $('#modal-deletar-aulas').modal('hide');
                        $.toast({
                            heading: 'Sucesso',
                            text: `${selectedCount} aula(s) excluída(s) com sucesso!`,
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#46c35f',
                            position: 'top-center'
                        });
                        table.ajax.reload();
                    } else {
                        $('#modal-deletar-aulas').modal('hide');
                        $.toast({
                            heading: response.includes('<br>') ? 'Atenção' : 'Erro',
                            text: response.replace(/<br>/g, '\n'),
                            showHideTransition: 'fade',
                            icon: response.includes('<br>') ? 'warning' : 'error',
                            loaderBg: response.includes('<br>') ? '#ffc107' : '#dc3545',
                            position: 'top-center',
                            hideAfter: !response.includes('<br>')
                        });
                        table.ajax.reload();
                    }
                },
                error: function() {
                    $.toast({
                        heading: 'Erro',
                        text: 'Erro inesperado ao processar a solicitação',
                        showHideTransition: 'fade',
                        icon: 'error',
                        loaderBg: '#dc3545',
                        position: 'top-center'
                    });
                },
                complete: function() {
                    $('#modal-deletar-aulas').find('button[type="submit"]')
                        .prop('disabled', false)
                        .text('Excluir');
                }
            });
        });
    });
</script>