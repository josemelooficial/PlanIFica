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
                    Confirma a exclusão das <span id="quantidade-aulas-selecionadas">0</span> aulas selecionadas?
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
        $('#deletarAulas').submit(function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let data = [];

            // Coletar os IDs das aulas selecionadas
            $('input[name="selecionados[]"]:checked').each(function() {
                data.push($(this).val());
            });

            if (data.length === 0) {
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

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    selecionados: data
                },
                success: function(response) {
                    if (response == "ok") {
                        // Fechar o modal
                        $('#modal-deletar-aulas').modal('hide');

                        // Mensagem de sucesso
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Todas as aulas selecionadas foram excluídas com sucesso!',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#46c35f',
                            position: 'top-center'
                        });

                        // Recarregar a tabela
                        if (typeof table !== 'undefined') {
                            table.ajax.reload();
                        }
                    } else {
                        // Fechar o modal mesmo com erros parciais
                        $('#modal-deletar-aulas').modal('hide');

                        // Verificar se é um erro complexo (com tags HTML)
                        if (response.includes('<br>')) {
                            // Mostrar em um toast mais elaborado ou modal de erros
                            $.toast({
                                heading: 'Atenção',
                                text: response.replace(/<br>/g, '\n'),
                                showHideTransition: 'fade',
                                icon: 'warning',
                                loaderBg: '#ffc107',
                                position: 'top-center',
                                hideAfter: false // Permanece até o usuário fechar
                            });
                        } else {
                            // Erro simples
                            $.toast({
                                heading: 'Erro',
                                text: response,
                                showHideTransition: 'fade',
                                icon: 'error',
                                loaderBg: '#dc3545',
                                position: 'top-center'
                            });
                        }

                        // Recarregar a tabela mesmo com erros (pode ter havido exclusões parciais)
                        if (typeof table !== 'undefined') {
                            table.ajax.reload();
                        }
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
                }
            });
        });
    });
</script>