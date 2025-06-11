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
                    Confirma a exclusão das aulas selecionadas?</div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger me-2">Excluir</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() 
    {
        $('#deletarAulas').submit(function(e) 
        {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let data = []; //form.serialize();            
            
            // Coletar os IDs das aulas selecionadas
            $('input[name="selecionados[]"]:checked').each(function() 
            {
                data.push($(this).val());
            });

            $.ajax(
                {
                type: 'POST',
                url: url,
                data: {selecionados: data},
                success: function(response) 
                {
                    if (response == "ok") 
                    {
                        // Mensagem de sucesso
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Aula cadastrada com sucesso!',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-center'
                        });

                        // Recarregar a tabela de aulas
                        table.ajax.reload();

                        $('#modal-deletar-aulas').modal('hide');
                    }
                    else
                    {
                        alert(response);
                    }
                },
                error: function() 
                {
                    alert('Erro ao processar a solicitação.');
                }
            });
        });
    });
</script>