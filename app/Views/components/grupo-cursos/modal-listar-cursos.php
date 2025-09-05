<?php if (!empty($grupos)): ?>
  <?php foreach ($grupos as $grupo): ?>
    <div class="modal fade" id="modal-list-gp-cursos-<?= $grupo['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal-edit-label-<?= $grupo['id'] ?>" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-edit-label-<?= $grupo['id'] ?>">Cursos do Grupo: <?= esc($grupo['nome']) ?></h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="table-responsive">
                <table class="table mb-4 custom-table">
                  <thead>
                    <tr>
                      <th>Nome do Curso</th>
                      <th>Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($grupo['cursos'])): ?>
                      <?php foreach ($grupo['cursos'] as $curso): ?>
                        <tr>
                          <td><?= esc($curso['nome']); ?></td>
                          <td>
                            <span data-bs-toggle="tooltip" data-placement="top" title="Remover curso do grupo">
                              <form action="<?= base_url('/sys/curso/remover-curso-grupo') ?>" method="POST">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="id" value="<?= $curso['id']; ?>">

                                <button
                                  type="submit"
                                  class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon me-1" >
                                  <i class="fa fa-minus"></i>
                                </button>
                              </form>
                            </span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="3">Nenhum curso associado a este grupo.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
