<div class="modal fade" id="modal-deletar-gp-cursos" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Confirmação necessária</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="deletarGrupoCursos" class="forms-sample" method="post" action="<?= base_url('sys/curso/deletar-grupo') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="deletar-gp-id" name="id" />
                <div class="modal-body text-break">
                    <div class="card card-inverse-warning">
                        <div class="card-body" style="padding:5px">
                            <p class="card-text">
                                O Grupo pode conter cursos vinculados, excluir o grupo também excluirá a relação com todos os cursos vinculados ao mesmo!
                            </p>
                        </div>
                    </div>
                    <br>

                    Confirma a exclusão do Grupo <strong id="deletar-gp-nome"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger me-2">Excluir</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
