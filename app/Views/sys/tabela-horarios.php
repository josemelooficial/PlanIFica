<style>
    .card-body.overflow-y-auto::-webkit-scrollbar,
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
        background-color: #000;
    }

    .card-body.overflow-y-auto::-webkit-scrollbar-track,
    .custom-scrollbar::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px rgba(6, 6, 6, 0.3);
        background: #f1f1f1;
        /* Cor de fundo da trilha */
    }

    .card-body.overflow-y-auto::-webkit-scrollbar-thumb,
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #333;
        outline: 1px solid slategrey;
        border-radius: 10px;
        /* Arredondamento do thumb */
    }

    .horario-vazio .card {
        max-width: 100% !important;
        margin: 0 !important;
        height: 100%;
    }

    .horario-vazio .card-body {
        padding: 0.25rem !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .horario-vazio h6 {
        font-size: 0.75rem !important;
        margin-bottom: 0.1rem !important;
    }

    .horario-vazio small {
        font-size: 0.65rem !important;
    }

    /* Mantenha as regras existentes */
    .card-body.overflow-y-auto::-webkit-scrollbar {
        width: 5px;
        background-color: #000;
    }

    .loader-demo-box {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        opacity: 0.9;
        background-color: #191c24;
        position: absolute;
        z-index: 9999;
        visibility: hidden;
    }

    .circle-loader::before {
        border-top-color: #8f5fe8
    }

    #loader-text {
        position: absolute;
        margin-top: 15vh;
        width: 100%;
        color: #fff;
        font-size: 12px;
        text-align: center;
    }

    .left-column-section {
        width: 100%;
    }

    .card.left-column-section {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .card-body.overflow-y-auto {
        flex: 1;
        min-height: 0;
    }

    .drag-over {
        background-color: #6c7293 !important;
    }

    .min-height-card {
        min-height: 80px;
        position: relative;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="modalAtribuirDisciplina" tabindex="-1" aria-labelledby="modalAtribuirDisciplinaLabel" aria-hidden="true" style="z-index: 10000;">
    <div class="modal-dialog" style="width: 1000px; max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalAtribuirDisciplinaLabel">
                    [<span id="modal_Turma"></span>] : [<span id="dia_da_aula"></span>] : [<span id="hora_da_aula"></span>]
                </h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tabelaDisciplinasModal" class="table">
                        <thead>
                            <tr>
                                <td colspan="4" class="text-center text-warning"> <i class="mdi mdi-alert-outline fs-6 me-1"></i> Atenção: ao atribuir uma nova disciplina, a atual será substituída.</td>
                            </tr>
                            <tr>
                                <th>Disciplina</th>
                                <th>Professor</th>
                                <th>Quantidade</th>
                                <!--<th>Ambiente</th>-->
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <thead>
                            <tr>
                                <td colspan="4" class="text-center text-info"><i class="mdi mdi-information-outline fs-6 me-1"></i> O ambiente onde ocorrerá a aula será definido no próximo passo.</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para seleção de ambiente -->
<div class="modal fade" id="modalSelecionarAmbiente" role="dialog" tabindex="-1" aria-labelledby="modalSelecionarAmbienteLabel" aria-hidden="false" style="z-index: 10000;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalSelecionarAmbienteLabel">Definir Ambiente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card border-1 shadow-sm bg-gradient">
                            <div class="card-body">
                                <h6 class="text-primary">
                                    <i class="mdi mdi-book-outline me-1"></i> <span id="modalAmbienteNomeDisciplina"></span>
                                </h6>
                                <div class="d-flex align-items-center mb-0 py-0">
                                    <i class="mdi mdi-account-tie fs-6 text-muted me-1"></i>
                                    <small class="text-secondary"><span id="modalAmbienteProfessor"></span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-1 shadow-sm">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="selectAmbiente">
                                        <h6 class="text-primary">Selecione o(s) ambiente(s):</h6>
                                    </label>
                                    <select class="form-select" id="selectAmbiente" multiple="multiple" name="selectAmbiente[]" style="width:100%;">
                                        <?php foreach ($ambientes as $ambiente): ?>
                                            <option 
                                                value="<?php echo esc($ambiente['id']) ?>"
                                                data-original-text="<?php echo esc($ambiente['nome']) ?>"
                                            >
                                                <?php echo esc($ambiente['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmarAmbiente">Confirmar</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal de Análise do Horário -->
<div class="modal fade" id="modalConfirmarRemocao" tabindex="-1" aria-labelledby="modalConfirmarRemocaoLabel" aria-hidden="true" style="z-index: 10001;">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="modalConfirmarRemocaoLabel">
                    <i class="mdi mdi-alert-circle-outline me-2 text-warning"></i> Análise de Horário
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="row" id="rowRestricao">
                    <h5 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Restrição do Docente!</h5>
                    <div class="card bg-dark border-danger mb-3">
                        <div class="card-body p-2">
                            <h5 class="text-danger mb-1">Este docente tem registro de restrição para o horário atribuído.</h5>
                        </div>
                    </div>
                </div>

                <div class="row" id="rowTresTurnos">
                    <h5 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Restrição do Docente!</h5>
                    <div class="card bg-dark border-danger mb-3">
                        <div class="card-body p-2">
                            <h5 class="text-danger mb-1">Este docente está alocado em três turnos em um mesmo dia.</h5>
                        </div>
                    </div>
                </div>

                <div class="row" id="rowIntervalo">
                    <h5 class="text-info"><i class="fa fa-exclamation-triangle"></i> Intervalo entre turnos!</h5>
                    <div class="card bg-dark border-info mb-3">
                        <div class="card-body p-2">
                            <h6 class="text-info mb-1" id="modalRemocaoIntervaloTipo">...</h6>
                            <h6 class="text-info mb-1" id="modalRemocaoIntervaloTempo">...</h6>
                            <h6 class="text-muted mb-1" id="modalRemocaoIntervaloCurso">...</h6>
                            <h6 class="text-muted mb-1" id="modalRemocaoIntervaloTurma">...</h6>
                            <p class="text-muted mb-1" id="modalRemocaoIntervaloDisciplina">...</p>
                        </div>
                    </div>
                </div>

                <div class="row" id="rowConflito">
                    <h5 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Conflito identificado!</h5>
                    <div class="card bg-dark border-danger mb-3">
                        <div class="card-body p-2">
                            <h6 class="text-warning mb-1" id="modalRemocaoConflitoCurso">...</h6>
                            <h6 class="text-warning mb-1" id="modalRemocaoConflitoTurma">...</h6>
                            <p class="text-warning mb-1" id="modalRemocaoConflitoDisciplina">...</p>
                            <p class="text-warning mb-1" id="modalRemocaoConflitoProfessor">...</p>
                            <p class="text-warning mb-1" id="modalRemocaoConflitoAmbiente">...</p>
                        </div>
                    </div>
                </div>

                <div class="row" id="rowAlterarAmbiente">
                    <div class="card bg-dark border-primary mb-3">
                        <div class="card-body p-1">
                            <label for="selectAmbiente">
                                <h6 class="text-primary">Selecione o(s) ambiente(s) para alterar:</h6>
                            </label>
                            <select class="form-select" id="alteraAmbiente" multiple="multiple" name="alteraAmbiente[]" style="width:100%;">
                                <?php foreach ($ambientes as $ambiente): ?>
                                    <option value="<?php echo esc($ambiente['id']) ?>"><?php echo esc($ambiente['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="text-end p-1">
                                <button type="button" class="btn btn-primary" id="confirmarAlterarAmbiente">Alterar Ambiente</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="card bg-dark border-warning mb-3">
                        <div class="card-body p-1">
                            <p class="text-warning mb-1"><strong>Deseja remover esta disciplina do horário?</strong></p>
                            <h6 class="text-muted mb-0" id="modalRemocaoDisciplina"></h6>
                            <small class="text-muted" id="modalRemocaoProfessor"></small><br />
                            <small class="text-muted" id="modalRemocaoAmbiente"></small>
                        </div>
                        <div class="text-end p-1">
                            <button type="button" class="btn btn-danger" id="confirmarRemocao">Remover</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!--só pra testar o modal de ambiente-->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSelecionarAmbiente">Launch demo modal</button> -->

<!-- Filtro -->
<div class="row g-3">
    <!-- Coluna esquerda - Filtros e Aulas Pendentes -->
    <div class="col-md-3 d-flex flex-column" style="position: relative; height: 74vh;">
        <!-- loader -->
        <div class="loader-demo-box">
            <div class="circle-loader"></div>
            <div id="loader-text">Carregando...</div>
        </div>

        <!-- Seção de Filtros -->
        <div class="card left-column-section mb-3" style="flex: 0 0 30%;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label for="curso">Curso:</label>
                            <select class="js-example-basic-single filtro" style="width:100%;" id="filtroCurso">
                                <option value=""></option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?php echo esc($curso['id']) ?>" data-regime="<?php echo esc($curso['regime']) ?>"><?php echo esc($curso['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label for="curso">Turma:</label>
                            <select class="js-example-basic-single filtro" style="width:100%;" id="filtroTurma">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Aulas Pendentes -->
        <div class="card left-column-section position-relative" style="flex: 1; min-height: 0;">

            <div class="card-body d-flex flex-column position-relative" style="height: 100%;">

                <div class="row">
                    <div class="col-md-7 text-sm-start">
                        <small>
                            Aulas Pendentes:
                            <span class="badge badge-pill badge-info" id="aulasCounter">-</span>
                        </small>
                    </div>
                    <div class="col-md-5 text-sm-end">
                        <button id="btn_limpar_horarios" type="button" class="btn btn-warning">
                            <i class="mdi mdi-calendar-remove"></i> Limpar
                        </button>
                    </div>
                </div>

                <!--<div class="row">
                    <div class="col-12 text-center">
                        <button id="btn_atribuir_automaticamente" type="button" class="btn btn-info" disabled>
                            <i class="mdi mdi-auto-fix"></i> Auto atribuir
                        </button>
                    </div>
                </div>-->

                <hr class="my-2" />

                <div class="position-absolute start-0 end-0" style="top: 130px; bottom: 15px;">
                    <div class="h-100 overflow-y-auto custom-scrollbar" id="aulasContainer" style="overflow-x: hidden;">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Tabela de Horários (Manhã, Tarde, Noite) - Lado direito (9 colunas) -->
    <div class="col-lg-9">
        <div class="card" style="height: 74vh;">
            <div class="card-body overflow-y-auto overflow-x-hidden">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="tabela-horarios" class="table table-bordered text-center mb-4">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    //Vertozão global pra guardar dados dos horários da turma
    var horarios = [];

    //Vertozão global pra guardar dados dos cursos
    var cursos = [];

    //Vertozão global pra guardar dados das aulas da turma
    var aulas = [];

    //Referencia para os nomes dos dias da semana
    var nome_dia = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'];

    $(document).ready(function() {
        function limparHorarios() {
            $('.horario-preenchido').each(function() {
                const aulaId = $(this).data('aula-id');
                var tempo_de_aula_id = $(this).attr('id').split('_')[1];

                if ($(`#horario_${tempo_de_aula_id}`).data('fixa') == 1) {
                    return;
                }

                // Requisição para remover a disciplina ao horário no backend
                $.post('<?php echo base_url('sys/tabela-horarios/removerAula'); ?>', {
                        aula_id: aulaId,
                        tempo_de_aula_id: tempo_de_aula_id
                    },
                    function(data) {
                        if (data == "1") {
                            moverDisciplinaParaPendentes($(`#horario_${tempo_de_aula_id}`));

                            // Limpa o horário
                            $(`#horario_${tempo_de_aula_id}`).html('')
                                .removeClass('horario-preenchido')
                                .addClass('horario-vazio')
                                .removeData(['disciplina', 'professor', 'ambiente', 'aula-id', 'aulas-total', 'aulas-pendentes'])
                                .off('click')
                                .click(function() {
                                    horarioSelecionado = $(this);
                                    carregarDisciplinasPendentes($(this).attr('id'));
                                    modalAtribuirDisciplina.show();
                                });

                            configurarDragAndDrop();
                        }
                    });
            });

            // Atualiza o contador de pendentes
            atualizarContadorPendentes();
        }

        function destacarAulaHorario(aula_horario_id, horarioId) {
            const btn = $(`#btnDestacar_horario_${aula_horario_id}`);
            const isDestaque = btn.hasClass("mdi-star");
            const tipo = isDestaque ? 0 : 1;

            $.ajax({
                url: '<?= site_url('sys/tabela-horarios/destacarAula') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    aula_horario_id: aula_horario_id,
                    tipo: tipo
                },
                success: function(response) {
                    if (response.success) {
                        if (tipo === 1) {
                            btn.removeClass("mdi-star-outline text-primary")
                                .addClass("mdi-star text-warning");
                            $(`#horario_${horarioId}`).data('destacada', 1);
                        } else {
                            btn.removeClass("mdi-star text-warning")
                                .addClass("mdi-star-outline text-primary");
                            $(`#horario_${horarioId}`).data('destacada', 0);
                        }

                        $.toast({
                            heading: 'Sucesso',
                            text: response.message || 'Operação realizada com sucesso',
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f96868',
                            position: 'top-center'
                        });
                    } else {
                        $.toast({
                            heading: 'Erro',
                            text: response.message || 'Não foi possível alterar o destaque',
                            showHideTransition: 'slide',
                            icon: 'error',
                            loaderBg: '#f96868',
                            position: 'top-center'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Falha na comunicação com o servidor';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    $.toast({
                        heading: 'Erro',
                        text: errorMsg,
                        showHideTransition: 'slide',
                        icon: 'error',
                        loaderBg: '#f96868',
                        position: 'top-center'
                    });
                }
            });
        }

        $("#btn_limpar_horarios").click(function() {
            if (confirm("Você tem certeza que deseja limpar todos os horários preenchidos? Esta ação não pode ser desfeita.")) {
                limparHorarios();
            }
        });

        $("#selectAmbiente").select2({
            dropdownParent: $('#modalSelecionarAmbiente')
        });
        $("#alteraAmbiente").select2({
            dropdownParent: $('#modalConfirmarRemocao')
        });

        // Define variáveis globais para armazenar os dados do modal
        const modalAtribuirDisciplinaElement = document.getElementById('modalAtribuirDisciplina');
        const modalSelecionarAmbienteElement = document.getElementById('modalSelecionarAmbiente');
        const modalConfirmarRemocaoElement = document.getElementById('modalConfirmarRemocao');

        // Inicializa os modais usando a API do Bootstrap 5
        const modalAtribuirDisciplina = new bootstrap.Modal(modalAtribuirDisciplinaElement);
        const modalSelecionarAmbiente = new bootstrap.Modal(modalSelecionarAmbienteElement);
        const modalConfirmarRemocao = new bootstrap.Modal(modalConfirmarRemocaoElement);

        //Algumas globais pra controle dos modals
        let horarioSelecionado = null;

        // Função para atualizar contador de pendentes
        function atualizarContadorPendentes() {
            let totalAulasPendentes = 0;
            $('.card[draggable="true"]').each(function() {
                totalAulasPendentes += parseInt($(this).data('aulas-pendentes'));
            });

            $('#aulasCounter').text(totalAulasPendentes);
        }

        function fixarAulaHorario(tipo, aula_horario_id, aula_id) {
            elemento = $(`#horario_${aula_id}`);

            $.post('<?php echo base_url('sys/tabela-horarios/fixarAula'); ?>', {
                    tipo: tipo, //1 = fixar, 0 = desfixar
                    aula_horario_id: aula_horario_id
                },
                function(data) {
                    if (data == "1") {
                        //encontrar o botão pelo nomezim e mudar a cor, além de desativar a remoção de alguma forma
                        if (tipo == 1) {
                            $("#btnFixar_horario_" + aula_horario_id)
                                .removeClass("text-primary")
                                .addClass("text-warning")
                                .off()
                                .click(function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    fixarAulaHorario(0, aula_horario_id, aula_id); //desfixar
                                });

                            $.toast({
                                heading: 'Sucesso',
                                text: 'A aula foi marcada como fixa no horário.',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-center'
                            });

                            elemento.data('fixa', 1);
                        } else {
                            $("#btnFixar_horario_" + aula_horario_id)
                                .removeClass("text-warning")
                                .addClass("text-primary")
                                .off()
                                .click(function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    fixarAulaHorario(1, aula_horario_id, aula_id); //fixar
                                });

                            $.toast({
                                heading: 'Sucesso',
                                text: 'A aula foi desmarcada como fixa no horário.',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-center'
                            });

                            elemento.data('fixa', 0);
                        }
                    } else {
                        // Mostra feedback de erro
                        $.toast({
                            heading: 'Erro',
                            text: 'Ocorreu um erro ao tentar fixar/desafixar a aula no horário.',
                            showHideTransition: 'slide',
                            icon: 'error',
                            loaderBg: '#f96868',
                            position: 'top-center'
                        });
                    }
                });
        }

        function bypassarAulaHorario(tipo, aula_horario_id, aula_id) {
            elemento = $(`#horario_${aula_id}`);

            $.post('<?php echo base_url('sys/tabela-horarios/bypassAula'); ?>', {
                    tipo: tipo, //1 = bypass, 0 = desbypass
                    aula_horario_id: aula_horario_id
                },
                function(data) {
                    if (data == "1") {
                        //encontrar o botão pelo nomezim e mudar a cor
                        if (tipo == 1) {
                            $("#btnBypass_horario_" + aula_horario_id)
                                .removeClass("text-primary")
                                .addClass("text-warning")
                                .off()
                                .click(function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    bypassarAulaHorario(0, aula_horario_id, aula_id); //desbypassar
                                });

                            $.toast({
                                heading: 'Sucesso',
                                text: 'A aula foi marcada como bypass no horário.',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-center'
                            });
                        } else {
                            $("#btnBypass_horario_" + aula_horario_id)
                                .removeClass("text-warning")
                                .addClass("text-primary")
                                .off()
                                .click(function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    bypassarAulaHorario(1, aula_horario_id, aula_id); //bypassar
                                });

                            $.toast({
                                heading: 'Sucesso',
                                text: 'A aula foi desmarcada como bypass no horário.',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-center'
                            });
                        }
                    } else {
                        // Mostra feedback de erro
                        $.toast({
                            heading: 'Erro',
                            text: 'Ocorreu um erro ao tentar adicionar ou remover bypass da aula no horário.',
                            showHideTransition: 'slide',
                            icon: 'error',
                            loaderBg: '#f96868',
                            position: 'top-center'
                        });
                    }
                });
        }

        // Função para mover disciplina de volta para pendentes
        function moverDisciplinaParaPendentes(horarioElement) {
            const $horario = $(horarioElement);
            const disciplina = $horario.data('disciplina');
            const professor = $horario.data('professor');
            const aulaId = $horario.data('aula-id');
            const aulasTotal = $horario.data('aulas-total') || '1';

            // Verifica se já existe na lista de pendentes
            if ($(`#aula_${aulaId}`).length > 0) {
                const cardAula = $(`#aula_${aulaId}`);
                const aulasPendentes = cardAula.data('aulas-pendentes') + 1;
                cardAula.data('aulas-pendentes', aulasPendentes);
                cardAula.find('.aulas-pendentes').text(aulasPendentes);
            } else {
                const cardAula = `
                    <div id="aula_${aulaId}" draggable="true" data-aula-id="${aulaId}" data-disciplina="${disciplina}" data-professor="${professor}" data-aulas-total="${aulasTotal}" data-aulas-pendentes="1" class="card border-1 shadow-sm mx-4 my-1 bg-gradient" style="cursor: pointer;">
                        <div class="card-body p-0 d-flex flex-column justify-content-center align-items-center text-center">
                            <h6 class="text-primary">
                                <i class="mdi mdi-book-outline me-1"></i> ${disciplina}
                            </h6>
                            <div class="d-flex align-items-center mb-0 py-0" id="professor_aula_${aulaId}">
                                <i class="mdi mdi-account-tie fs-6 text-muted me-1"></i>
                                <small class="text-secondary">${professor}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-door fs-6 text-muted me-1"></i>
                                <small class="text-secondary"><span class="aulas-pendentes">1</span> aula(s)</small>
                            </div>
                        </div>
                    </div>
                `;

                $('#aulasContainer').append(cardAula);

                configurarDragAndDrop(); // Reconfigura eventos para o novo card
            }

            atualizarContadorPendentes();
        }

        // Modal de confirmação de remoção (estilo Bootstrap)
        function mostrarModalConfirmacaoRemocao(horarioElement) 
        {
            const $horario = $(horarioElement);
            const aulaId = $horario.data('aula-id');

            //const modalConfirmarRemocao = new bootstrap.Modal(document.getElementById('modalConfirmarRemocao'));

            // Adiciona os dados ao horário
            $("#modalConfirmarRemocao")
                .data('aula-id', aulaId)
                .data('aula_horario_id', $horario.data('aula_horario_id'))
                .data('horario_id', $horario.attr('id').split('_')[1]);

            // Preenche os dados no modal
            $('#modalRemocaoDisciplina').text($horario.data('disciplina'));
            $('#modalRemocaoProfessor').text('Professor(es): ' + $horario.data('professor'));
            $('#modalRemocaoAmbiente').text('Ambiente(s): ' + $horario.data('ambienteNome').join(", "));

            $('#rowRestricao').hide();
            $('#rowConflito').hide();
            //$('#rowAlterarAmbiente').hide();
            $('#rowTresTurnos').hide();
            $('#rowIntervalo').hide();

            if ($horario.data('fixa') == 1) {
                $("#confirmarRemocao").prop("disabled", true);
            } else {
                $("#confirmarRemocao").prop("disabled", false);
            }

            var selectedAmbientes = [];

            $.each($horario.data('ambienteNome'), function(index, value) 
            {

                selectedAmbientes.push(getAmbienteId(value));
            });

            $('#alteraAmbiente').val(selectedAmbientes);
            $('#alteraAmbiente').trigger('change');

            //Verificar e preencher dados do conflito
            if ($horario.data('tresturnos') > 0) {
                $('#rowTresTurnos').show();
            } else if ($horario.data('intervalo') != 0) {
                /*<h6 class="text-warning mb-1" id="modalRemocaoIntervaloTipo">...</h6>
                <h6 class="text-warning mb-1" id="modalRemocaoIntervaloTempo">...</h6>*/

                // Requisição para buscar os dados da aula causando problema de intervalo
                $.get('<?php echo base_url('sys/tabela-horarios/dadosDaAula/'); ?>' + $horario.data('intervalo').split('-')[2],
                    function(data) {
                        $('#modalRemocaoIntervaloCurso').text("Curso: " + data[0].curso);
                        $('#modalRemocaoIntervaloTurma').text("Turma: " + data[0].turma);
                        $('#modalRemocaoIntervaloDisciplina').text("Disciplina: " + data[0].disciplina);

                        var motivo = $horario.data('intervalo').split('-')[0];

                        var timestamp = $horario.data('intervalo').split('-')[1];
                        var horas = parseInt(timestamp / 60);
                        var minutos = parseInt(timestamp % 60);
                        timestamp = horas + "h e " + minutos + "m";
                        $('#modalRemocaoIntervaloTempo').text("Tempo: " + timestamp);

                        switch (motivo) {
                            case '1':
                                $('#modalRemocaoIntervaloTipo').text("Intervalo entre manhã e tarde (mínimo 01 hora).");
                                break;
                            case '2':
                                $('#modalRemocaoIntervaloTipo').text("Intervalo entre tarde e noite (mínimo 01 hora).");
                                break;
                            case '3':
                                $('#modalRemocaoIntervaloTipo').text("Intervalo entre noite e manhã (mínimo 11 horas).");
                                break;
                            case '4':
                                $('#modalRemocaoIntervaloTipo').text("Intervalo entre noite e manhã (mínimo 11 horas).");
                                break;
                        }

                    }, 'json');

                $('#rowIntervalo').show();

            } else if ($horario.data('restricao') > 0) {
                $('#rowRestricao').show();
            } else if ($horario.data('conflito') > 0) {
                // Requisição para buscar os dados da aula em conflito
                $.get('<?php echo base_url('sys/tabela-horarios/dadosDaAula/'); ?>' + $horario.data('conflito'),
                    function(data) {
                        $('#modalRemocaoConflitoCurso').text("Curso: " + data[0].curso);
                        $('#modalRemocaoConflitoTurma').text("Turma: " + data[0].turma);
                        $('#modalRemocaoConflitoDisciplina').text("Disciplina: " + data[0].disciplina);

                        var professores = data[0].professor;
                        var ambientes = data[0].ambiente;

                        data.forEach(function(value) {
                            if (professores.indexOf(value.professor) < 0)
                                professores += ", " + value.professor;

                            if (ambientes.indexOf(value.ambiente) < 0)
                                ambientes += ", " + value.ambiente;
                        });

                        if ($horario.data('conflitoProfessor') == 1) {
                            $('#modalRemocaoConflitoProfessor')
                                .html('<i class="fa fa-exclamation-circle me-1"></i> ' + 'Professor(es): ' + professores)
                                .addClass('text-danger')
                                .removeClass('text-warning');
                        } else {
                            $('#modalRemocaoConflitoProfessor')
                                .text("Professor(es): " + professores)
                                .addClass('text-warning')
                                .removeClass('text-danger');
                        }

                        if ($horario.data('conflitoAmbiente') == 1) {
                            $('#modalRemocaoConflitoAmbiente')
                                .html('<i class="fa fa-exclamation-circle me-1"></i> ' + 'Ambiente(s): ' + ambientes)
                                .addClass('text-danger')
                                .removeClass('text-warning');
                        } else {
                            $('#modalRemocaoConflitoAmbiente')
                                .text("Ambiente(s): " + ambientes)
                                .addClass('text-warning')
                                .removeClass('text-danger');
                        }

                        /*$.each(data, function(index, value)
                        {
                            if($('#modalRemocaoConflitoAmbiente').html().indexOf(value.ambiente) < 0)
                            {
                                $('#modalRemocaoConflitoAmbiente').append(value.ambiente + " | ");
                            }
                        });*/

                    }, 'json');

                $('#rowConflito').show();
            }

            // Remove qualquer evento anterior do botão de confirmação
            $('#confirmarRemocao').off('click');

            // Configura o evento de confirmação
            $('#confirmarRemocao').on('click', function() {
                horarioId = $horario.attr('id').split('_')[1]; // Extrai o ID do horário

                // Requisição para remover a disciplina ao horário no backend
                $.post('<?php echo base_url('sys/tabela-horarios/removerAula'); ?>', {
                        aula_id: aulaId,
                        tempo_de_aula_id: horarioId
                    },
                    function(data) {
                        if (data == "1") {
                            moverDisciplinaParaPendentes(horarioElement);

                            // Limpa o horário
                            $horario.html('')
                                .removeClass('horario-preenchido')
                                .addClass('horario-vazio')
                                .removeData(['disciplina', 'professor', 'ambiente', 'aula-id', 'aulas-total', 'aulas-pendentes'])
                                .off('click')
                                .click(function() {
                                    horarioSelecionado = $(this);
                                    carregarDisciplinasPendentes($(this).attr('id'));
                                    modalAtribuirDisciplina.show();
                                });

                            configurarDragAndDrop();

                            // Fecha o modal
                            modalConfirmarRemocao.hide();

                            // Mostra feedback de sucesso
                            $.toast({
                                heading: 'Sucesso',
                                text: 'A disciplina foi removida do horário.',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-center'
                            });
                        } else {
                            // Mostra feedback de erro
                            $.toast({
                                heading: 'Erro',
                                text: 'Ocorreu um erro ao remover a aula do horário.',
                                showHideTransition: 'slide',
                                icon: 'error',
                                loaderBg: '#f96868',
                                position: 'top-center'
                            });
                        }
                    });
            });

            // Mostra o modal
            modalConfirmarRemocao.show();
        }

        // Configura drag and drop
        function configurarDragAndDrop() {
            // Drag start para cards de disciplinas
            $('.card[draggable="true"]').on('dragstart', function(e) {
                e.originalEvent.dataTransfer.setData('text/plain', $(this).data('aula-id'));
                $(this).addClass('dragging');
            });

            // Drag end para cards de disciplinas
            $('.card[draggable="true"]').on('dragend', function() {
                $(this).removeClass('dragging');
            });

            // Drag over para horários
            $('.horario-vazio').on('dragover', function(e) {
                e.preventDefault();

                $(this).addClass('drag-over');
            });

            // Drag leave para horários
            $('.horario-vazio').on('dragleave', function() {
                $(this).removeClass('drag-over');
            });

            // Drop para horários
            $('.horario-vazio').on('drop', function(e) {
                e.preventDefault();

                $(this).removeClass('drag-over');

                horarioId = $(this).attr('id').split('_')[1]; // Extrai o ID do horário

                const aulaId = e.originalEvent.dataTransfer.getData('text/plain');

                if ($(this).html().trim() !== "") {
                    return; // Se o horário já contém uma disciplina, não faz nada
                }

                if (aulaId) {
                    horarioSelecionado = $(this);
                    atribuirDisciplina(aulaId, horarioId);
                }
            });
        }

        // Função para atribuir disciplina ao horário selecionado
        function atribuirDisciplina(aulaId, horarioId) {
            modalAtribuirDisciplina.hide();

            // Pequeno delay para garantir que o modal feche antes de abrir o próximo
            setTimeout(() => {
                abrirModalAmbiente(aulaId, horarioId);
            }, 100);
        }

        const $modalAmbiente  = $('#modalAula');
        const $selectAmbiente = $('#selectAmbiente');

        let conflitosDetectados = null;

        const textoConflito = ' ⚠️';

        // config para não quebrar o select 
        const configSelectAmbiente = {
            width: '100%',
            placeholder: 'Selecione o(s) ambiente(s)…',
            allowClear: true,
            closeOnSelect: false,
            language: { noResults: () => 'Sem resultados' },
            templateResult: function (data) {
                if (!data.id) return data.text;

                const $optionAmbiente = $(data.element);
                // se houver conflito, altera a cor e mostra o texto com conflito
                // se não, mostra apenas o texto padrão
                return $optionAmbiente.hasClass('option-conflito')
                ? $('<span class="text-secondary"></span>').text($optionAmbiente.text())
                : data.text;
            }
        };

        //função para destruir o select2 possibilitando que as opções marcadas com conflito sejam limpas antes da próxima abertura de modal
        function destroySelect2() {
            if ($selectAmbiente.hasClass('select2-hidden-accessible')) {
                $selectAmbiente.select2('close');
                $selectAmbiente.select2('destroy');
            }
        }

        function inicializarSelect2() {
            if ($selectAmbiente.hasClass('select2-hidden-accessible')) return; // se já iniciado, retorna

            const $selectNaModal = $selectAmbiente.closest('.modal');
            const fallbackSelect = $selectNaModal.length ? $selectNaModal : $(document.body);//evita erro caso o select não esteja dentro da modal ainda

            $selectAmbiente.select2({
                ...configSelectAmbiente,
                dropdownParent: fallbackSelect
            });
        }

        function limparOptionsSelect() {
            //percorre todas as opcões do select e remove o texto de conflito, se houver
            $selectAmbiente.find('option').each(function () {
                const $optionAmbiente = $(this);
                const textoPadrao = $optionAmbiente.attr('data-original-text') ?? $optionAmbiente.text().replace(textoConflito, '');
                $optionAmbiente.text(textoPadrao).removeClass('option-conflito').prop('disabled', false);
            });
            $selectAmbiente.val(null);
        }

        function abrirModalAmbiente(aulaId, tempoDeAulaId) 
        {        
            //evita que uma requisição seja chamada antes da finalização da anterior     
            if (conflitosDetectados && conflitosDetectados.readyState !== 4) 
                conflitosDetectados.abort();

            //desmonta select2 e limpa antes de mexer no HTML
            destroySelect2();
            limparOptionsSelect();

            conflitosDetectados = $.ajax({
                url: '<?= base_url('sys/tabela-horarios/destacar-conflitos-ambiente'); ?>',
                method: 'POST',
                dataType: 'json',
                cache: false,
                data: { aula_id: aulaId, tempo_de_aula_id: tempoDeAulaId }
            });

            conflitosDetectados.done(function (data) 
            {
                const arr = Array.isArray(data) ? data : [];
                const conflitoIds = new Set(arr.map(o => String(o.ambiente_id)));

                $selectAmbiente.find('option:disabled:selected').remove();
                $selectAmbiente.prop('disabled', false);

                //adiciona a tag de conflito à option caso detecte o conflito 
                $selectAmbiente.find('option').each(function () 
                {
                    const $optionAmbiente = $(this);
                    const id = String($optionAmbiente.val());
                    const textoPadrao = $optionAmbiente.attr('data-original-text') ?? $optionAmbiente.text().replace(textoConflito, '');

                    if (conflitoIds.has(id)) 
                    {
                        $optionAmbiente.text(textoPadrao + textoConflito).addClass('option-conflito');
                    }
                    else
                    {
                        $optionAmbiente.text(textoPadrao).removeClass('option-conflito');
                    }
                });

                //garantir que o Select2 esteja ativo para evitar que ele não consiga ser aberto
                inicializarSelect2();
                $selectAmbiente.trigger('change');
            });

            //se falhar, isso garante que o select2 continue sendo inicializado
            conflitosDetectados.fail(function (xhr, status, err) 
            {
                if (status === 'abort') 
                    return;

                $selectAmbiente.find('option:disabled:selected').remove();
                $selectAmbiente.prop('disabled', false);

                inicializarSelect2();
                $selectAmbiente.trigger('change');

                console.warn('Falha ao carregar conflitos:', status, err, 'HTTP', xhr?.status);
            });

            // !ditando ciclo de vida da modal para o select não acumular estados! //

            $modalAmbiente.on('show.bs.modal', function (e) 
            {
                const $aberturaModal = $(e.relatedTarget);
                abrirModalAmbiente($aberturaModal.data('aula-id'), $aberturaModal.data('tempo-de-aula-id'));
            });

            // iniciando o Select2 após a modal estar no DOM
            $modalAmbiente.on('shown.bs.modal', function () 
            {
                inicializarSelect2();
            });

            $modalAmbiente.on('hidden.bs.modal', function () 
            {
                if (conflitosDetectados && conflitosDetectados.readyState !== 4) 
                    conflitosDetectados.abort();

                conflitosDetectados = null;
                destroySelect2();
                limparOptionsSelect();
            });

            let minhaAula = getAulaById(aulaId);
            $("#modalAmbienteNomeDisciplina").html(minhaAula.disciplina);
            $("#modalAmbienteProfessor").html(minhaAula.professores.join(", "));
            $("#modalAmbienteAulas").html("1 aula"); // Sempre atribui 1 aula por vez

            // Armazena o ID da aula e horario para uso posterior
            $('#modalSelecionarAmbiente').data('aula-id', aulaId).data('horario-id', horarioId);

            modalSelecionarAmbiente.show();
        }

        // Configura o evento de confirmação do ambiente
        $("#confirmarAmbiente").click(function(e) 
        {
            e.preventDefault();
            e.stopPropagation();

            const ambienteSelecionadoId = $("#selectAmbiente").val();
            var ambientesSelecionadosNome = [];

            var data = $('#selectAmbiente').select2('data');
            data.forEach(function(item) 
            {
                item.text = item.text.replace(textoConflito, '');
                ambientesSelecionadosNome.push(item.text);
            });

            const aulaId = $('#modalSelecionarAmbiente').data('aula-id');
            const aula = getAulaById(aulaId);
            const cardAula = $(`#aula_${aulaId}`);
            const horarioId = $('#modalSelecionarAmbiente').data('horario-id');

            if (horarioSelecionado) 
            {
                // Requisição para atribuir a disciplina ao horário no backend
                $.post('<?php echo base_url('sys/tabela-horarios/atribuirAula'); ?>', {
                        aula_id: aulaId,
                        tempo_de_aula_id: horarioId,
                        ambiente_id: ambienteSelecionadoId
                    },
                    function(data) {
                        if (data == "0") {
                            $.toast({
                                heading: 'Erro',
                                text: 'Ocorreu um erro ao atribuir a disciplina ao horário.',
                                showHideTransition: 'slide',
                                icon: 'error',
                                loaderBg: '#f96868',
                                position: 'top-center'
                            });
                            return;
                        } else if (data.indexOf("OK") >= 0 || data.indexOf("CONFLITO") >= 0 || data.indexOf("RESTRICAO") >= 0 || data.indexOf("TRES-TURNOS") >= 0 || data.indexOf("INTERVALO") >= 0) {
                            var conflitoStyle = "text-primary";
                            var conflitoIcon = "fa-mortar-board";
                            var aulaConflito = 0;
                            var tresTurnos = 0;
                            var restricao = 0;
                            var intervalo = 0;
                            var conflitoAmbiente = 0;
                            var conflitoProfessor = 0;

                            var aulaHorarioId = data.split("-")[0];

                            if (data.indexOf("TRES-TURNOS") >= 0) {
                                conflitoStyle = "text-danger";
                                conflitoIcon = "fa-warning";
                                tresTurnos = 1;
                            } else if (data.indexOf("RESTRICAO") >= 0) {
                                conflitoStyle = "text-danger";
                                conflitoIcon = "fa-warning";
                                restricao = data.split("-")[3];
                            } else if (data.indexOf("AMBIENTE") >= 0) {
                                aulaConflito = data.split("-")[3];
                                conflitoStyle = "text-warning";
                                conflitoIcon = "fa-warning";
                                conflitoAmbiente = 1;
                            } else if (data.indexOf("PROFESSOR") >= 0) {
                                aulaConflito = data.split("-")[3];
                                conflitoStyle = "text-warning";
                                conflitoIcon = "fa-warning";
                                conflitoProfessor = 1;
                            } else if (data.indexOf("INTERVALO") >= 0) {
                                conflitoStyle = "text-info";
                                conflitoIcon = "fa-warning";
                                intervalo = data;
                            }

                            // Preenche o horário selecionado
                            horarioSelecionado.html(`
                            <div class="card border-1 shadow-sm bg-gradient min-height-card" style="cursor: pointer; height: 100%;">
                                <div class="card-body p-1 d-flex flex-column justify-content-center align-items-center text-center">
                                    <h6 class="text-wrap mb-0 fs-6 ${conflitoStyle}" style="font-size: 0.75rem !important; margin-right: 15px">
                                        <i class="fa ${conflitoIcon} me-1"></i>
                                        ${aula.disciplina}
                                    </h6>
                                    <div class="d-flex align-items-center mb-0 py-0" style="margin-right: 15px">
                                        <i class="mdi mdi-account-tie fs-6 text-muted me-1"></i>
                                        <small class="text-wrap text-secondary" style="font-size: 0.65rem !important;">${aula.professores.join(", ")}</small>
                                    </div>
                                    <div class="d-flex align-items-center" style="margin-right: 15px">
                                        <i class="mdi mdi-door fs-6 text-muted me-1"></i>
                                        <small class="text-wrap text-secondary" style="font-size: 0.65rem !important;">${ambientesSelecionadosNome.join("<br />")}</small>
                                    </div>
                                    <div style="width: 100%; text-align: right; top: 0; position: absolute">
                                        <i class="mdi mdi-close-box fs-6 text-danger me-1" id="btnRemover_horario_${aulaHorarioId}"></i><br />
                                        <i class="mdi mdi-lock fs-6 text-primary me-1" id="btnFixar_horario_${aulaHorarioId}"></i><br />
                                        <i class="mdi mdi-account-multiple fs-6 text-primary me-1" id="btnBypass_horario_${aulaHorarioId}"></i><br />
                                        <i class="mdi ${(aula.destaque == 1) ? 'mdi-star text-warning' : 'mdi-star-outline text-primary'} fs-6 me-1" id="btnDestacar_horario_${aulaHorarioId}"></i>
                                    </div>
                                </div>
                            </div>
                        `);

                            $("#btnFixar_horario_" + aulaHorarioId).off().click(function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                fixarAulaHorario(1, aulaHorarioId, horarioId);
                            });

                            $("#btnBypass_horario_" + aulaHorarioId).off().click(function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                bypassarAulaHorario(1, aulaHorarioId, horarioId);
                            });

                            $("#btnDestacar_horario_" + aulaHorarioId).off().click(function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                destacarAulaHorario(aulaHorarioId, horarioId);
                            });

                            $("#btnRemover_horario_" + aulaHorarioId).off().click(function(e) {
                                e.preventDefault();
                                e.stopPropagation();

                                if ($(`#horario_${horarioId}`).data('fixa') == 1) {
                                    alert("Aula fixada, não pode ser removida");
                                    return;
                                }

                                // Requisição para remover a disciplina ao horário no backend
                                $.post('<?php echo base_url('sys/tabela-horarios/removerAula'); ?>', {
                                        aula_id: aulaId,
                                        tempo_de_aula_id: horarioId
                                    },
                                    function(data) {
                                        if (data == "1") {
                                            moverDisciplinaParaPendentes($(`#horario_${horarioId}`));

                                            // Limpa o horário
                                            $(`#horario_${horarioId}`).html('')
                                                .removeClass('horario-preenchido')
                                                .addClass('horario-vazio')
                                                .removeData(['disciplina', 'professor', 'ambiente', 'aula-id', 'aulas-total', 'aulas-pendentes'])
                                                .off('click')
                                                .click(function() {
                                                    horarioSelecionado = $(this);
                                                    carregarDisciplinasPendentes($(this).attr('id'));
                                                    modalAtribuirDisciplina.show();
                                                });

                                            configurarDragAndDrop();

                                            // Mostra feedback de sucesso
                                            $.toast({
                                                heading: 'Sucesso',
                                                text: 'A disciplina foi removida do horário.',
                                                showHideTransition: 'slide',
                                                icon: 'success',
                                                loaderBg: '#f96868',
                                                position: 'top-center'
                                            });
                                        } else {
                                            // Mostra feedback de erro
                                            $.toast({
                                                heading: 'Erro',
                                                text: 'Ocorreu um erro ao remover a aula do horário.',
                                                showHideTransition: 'slide',
                                                icon: 'error',
                                                loaderBg: '#f96868',
                                                position: 'top-center'
                                            });
                                        }
                                    });
                            });

                            // Adiciona os dados ao horário
                            horarioSelecionado
                                .data('disciplina', aula.disciplina)
                                .data('professor', aula.professores.join(", "))
                                .data('ambiente', ambienteSelecionadoId)
                                .data('ambienteNome', ambientesSelecionadosNome)
                                .data('aula-id', aulaId)
                                .data('aulas-total', cardAula.data('aulas-total'))
                                .data('aulas-pendentes', cardAula.data('aulas-pendentes'))
                                .data('conflito', aulaConflito)
                                .data('conflitoAmbiente', conflitoAmbiente)
                                .data('conflitoProfessor', conflitoProfessor)
                                .data('restricao', restricao)
                                .data('tresturnos', tresTurnos)
                                .data('intervalo', intervalo)
                                .data('aula_horario_id', aulaHorarioId)
                                .data('fixa', 0)
                                .data('destacada', (aula.destaque == 1) ? 1 : 0)
                                .removeClass('horario-vazio')
                                .addClass('horario-preenchido')
                                .off()
                                .click(function() {
                                    mostrarModalConfirmacaoRemocao(this);
                                });

                            // Atualiza a quantidade de aulas pendentes no card
                            const aulasPendentes = cardAula.data('aulas-pendentes') - 1;
                            cardAula.data('aulas-pendentes', aulasPendentes);
                            cardAula.find('.aulas-pendentes').text(aulasPendentes);

                            // Se zerou, remove o card
                            if (aulasPendentes <= 0) {
                                cardAula.remove();
                            }

                            atualizarContadorPendentes();
                            modalSelecionarAmbiente.hide();

                            // Mostra feedback de sucesso
                            $.toast({
                                heading: 'Sucesso',
                                text: 'A disciplina foi atribuída ao horário.',
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f96868',
                                position: 'top-center'
                            });
                        }
                    });
            }
        });

        // Configura o evento de confirmação do ambiente
        $("#confirmarAlterarAmbiente").click(function(e) {
            e.preventDefault();
            e.stopPropagation();

            const ambienteSelecionadoId = $("#alteraAmbiente").val();

            var ambientesSelecionadosNome = [];

            var data = $('#alteraAmbiente').select2('data');
            data.forEach(function(item) {
                ambientesSelecionadosNome.push(item.text);
            });

            const aulaId = $('#modalConfirmarRemocao').data('aula-id');
            const aula = getAulaById(aulaId);
            const cardAula = $(`#aula_${aulaId}`);
            const horarioId = $('#modalConfirmarRemocao').data('horario_id');

            // Requisição para atribuir a disciplina ao horário no backend
            $.post('<?php echo base_url('sys/tabela-horarios/atribuirAula'); ?>', {
                    aula_id: aulaId,
                    tempo_de_aula_id: horarioId,
                    ambiente_id: ambienteSelecionadoId
                },
                function(data) {
                    if (data == "0") {
                        $.toast({
                            heading: 'Erro',
                            text: 'Ocorreu um erro ao tentar alterar o ambiente.',
                            showHideTransition: 'slide',
                            icon: 'error',
                            loaderBg: '#f96868',
                            position: 'top-center'
                        });
                        return;
                    } else if (data.indexOf("OK") >= 0 || data.indexOf("CONFLITO") >= 0) {
                        var conflitoStyle = "text-primary";
                        var conflitoIcon = "fa-mortar-board";
                        var aulaConflito = 0;
                        var tresTurnos = 0;
                        var restricao = 0;
                        var intervalo = 0;
                        var conflitoAmbiente = 0;
                        var conflitoProfessor = 0;

                        var aulaHorarioId = data.split("-")[0];

                        if (data.indexOf("AMBIENTE") >= 0) {
                            aulaConflito = data.split("-")[3];
                            conflitoStyle = "text-warning";
                            conflitoIcon = "fa-warning";
                            conflitoAmbiente = 1;
                        }

                        // Preenche o horário selecionado
                        $(`#horario_${horarioId}`).html(`
                        <div class="card border-1 shadow-sm bg-gradient min-height-card" style="cursor: pointer; height: 100%;">
                            <div class="card-body p-1 d-flex flex-column justify-content-center align-items-center text-center">
                                <h6 class="text-wrap mb-0 fs-6 ${conflitoStyle}" style="font-size: 0.75rem !important; margin-right: 15px">
                                    <i class="fa ${conflitoIcon} me-1"></i>
                                    ${aula.disciplina}
                                </h6>
                                <div class="d-flex align-items-center mb-0 py-0" style="margin-right: 15px">
                                    <i class="mdi mdi-account-tie fs-6 text-muted me-1"></i>
                                    <small class="text-wrap text-secondary" style="font-size: 0.65rem !important;">${aula.professores.join(", ")}</small>
                                </div>
                                <div class="d-flex align-items-center" style="margin-right: 15px">
                                    <i class="mdi mdi-door fs-6 text-muted me-1"></i>
                                    <small class="text-wrap text-secondary" style="font-size: 0.65rem !important;">${ambientesSelecionadosNome.join("<br />")}</small>
                                </div>
                                <div style="width: 100%; text-align: right; top: 0; position: absolute">
                                    <i class="mdi mdi-close-box fs-6 text-danger me-1" id="btnRemover_horario_${aulaHorarioId}"></i><br />
                                    <i class="mdi mdi-lock fs-6 text-primary me-1" id="btnFixar_horario_${aulaHorarioId}"></i><br />
                                    <i class="mdi mdi-account-multiple fs-6 text-primary me-1" id="btnBypass_horario_${aulaHorarioId}"></i><br />
                                    <i class="mdi ${(aula.destaque == 1) ? 'mdi-star text-warning' : 'mdi-star-outline text-primary'} fs-6 me-1" id="btnDestacar_horario_${aulaHorarioId}"></i>
                                </div>
                            </div>
                        </div>
                    `);

                        // Adiciona os dados ao horário
                        $(`#horario_${horarioId}`)
                            .data('disciplina', aula.disciplina)
                            .data('professor', aula.professores.join(", "))
                            .data('ambiente', ambienteSelecionadoId)
                            .data('ambienteNome', ambientesSelecionadosNome)
                            .data('aula-id', aulaId)
                            .data('aulas-total', cardAula.data('aulas-total'))
                            .data('aulas-pendentes', cardAula.data('aulas-pendentes'))
                            .data('conflito', aulaConflito)
                            .data('conflitoAmbiente', conflitoAmbiente)
                            .data('conflitoProfessor', conflitoProfessor)
                            .data('restricao', restricao)
                            .data('tresturnos', tresTurnos)
                            .data('intervalo', intervalo)
                            .data('aula_horario_id', aulaHorarioId)
                            .data('fixa', 0)
                            .data('destacada', (aula.destaque == 1) ? 1 : 0)
                            .removeClass('horario-vazio')
                            .addClass('horario-preenchido')
                            .off()
                            .click(function() {
                                mostrarModalConfirmacaoRemocao(this);
                            });

                        // Configura eventos dos botões
                        $("#btnDestacar_horario_" + aulaHorarioId).off().click(function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            destacarAulaHorario(aulaHorarioId, horarioId);
                        });
                    }

                    modalConfirmarRemocao.hide();

                    // Mostra feedback de sucesso
                    $.toast({
                        heading: 'Sucesso',
                        text: 'Ambiente(s) alterado(s) com sucesso.',
                        showHideTransition: 'slide',
                        icon: 'success',
                        loaderBg: '#f96868',
                        position: 'top-center'
                    });
                });
        });

        // Carrega as disciplinas pendentes no modal
        function carregarDisciplinasPendentes(id) {
            id = id.split('_')[1]; // Extrai o ID do horário
            var dadosDoHorario = getHorarioById(id);

            $("#dia_da_aula").html(nome_dia[dadosDoHorario.dia_semana]);
            $("#hora_da_aula").html(dadosDoHorario.hora_inicio + ":" + dadosDoHorario.minuto_inicio);
            $("#modal_Turma").html($('#filtroTurma option:selected').text());

            $("#tabelaDisciplinasModal tbody").empty();

            // Verifica se há uma disciplina atribuída no horário selecionado
            if (horarioSelecionado && horarioSelecionado.data('disciplina')) {
                const row = `
                    <tr>
                        <td>${horarioSelecionado.data('disciplina')}</td>
                        <td>${horarioSelecionado.data('professor')}</td>
                        <td>1 aula</td>
                        <td><button class="btn btn-danger btn-sm btn-remover">Remover</button></td>
                    </tr>
                `;

                $("#tabelaDisciplinasModal tbody").append(row);

                // Evento para botão remover
                $("#tabelaDisciplinasModal .btn-remover").click(function() {
                    mostrarModalConfirmacaoRemocao(horarioSelecionado[0]);
                    modalAtribuirDisciplina.hide();
                });
            }

            $('.card[draggable="true"]').each(function() {
                var theCard = $(this);

                var disciplinaRow = '' +
                    '<tr>' +
                    '<td>' + $(this).data("disciplina") + '</td>' +
                    '<td>' + $(this).data("professor") + '</td>' +
                    '<td>' + $(this).data("aulas-pendentes") + ' aula(s)</td>' +
                    '<td>' +
                    '<button type="button" class="btn btn-primary btn-sm botao_atribuir" id="botao_atribuir_' + $(this).data("aula-id") + '" >Atribuir</button>' +
                    '</td>' +
                    '</tr>';

                $("#tabelaDisciplinasModal tbody").append(disciplinaRow);

                // Adiciona evento de clique diretamente
                $("#botao_atribuir_" + $(this).data("aula-id")).on('click', function() {
                    atribuirDisciplina($(this).attr('id').split('_')[2], id);
                });
            });
        }

        //Função para pesquisar o id de um horário pelo dia e horários
        function getIdByDiaHoraMinuto(vetor, dia, hora_inicio, minuto_inicio, hora_fim, minuto_fim) {
            var id = 0;

            $.each(vetor, function(idx, obj) {
                if (obj.dia_semana == dia && obj.hora_inicio == hora_inicio && obj.minuto_inicio == minuto_inicio && obj.hora_fim == hora_fim && obj.minuto_fim == minuto_fim) {
                    id = obj.id;
                    return false; //simula o BREAK no .each do JQuery
                }
            });

            return id;
        }

        //Função para retornar os dados de um horário pelo id
        function getHorarioById(id) {
            let theIdObj = null;

            $.each(horarios, function(idx, obj) {
                if (obj.id == id) {
                    theIdObj = obj;
                    return false; //simula o BREAK no .each do JQuery
                }
            });

            return theIdObj;
        }

        //Função para retornar os dados de uma aula pelo id
        function getAulaById(id) {
            let theIdObj = null;

            $.each(aulas, function(idx, obj) {
                if (obj.id == id) {
                    theIdObj = obj;
                    return false; //simula o BREAK no .each do JQuery
                }
            });

            return theIdObj;
        }

        function getAmbienteNome(id) 
        {
            var ambienteNome = "";

            $("#selectAmbiente option").each(function() 
            {
                if ($(this).val() == id) 
                {
                    ambienteNome = $(this).text();
                }
            });

            return ambienteNome;
        }

        function getAmbienteId(nome) 
        {
            var ambienteId = -1;

            $("#selectAmbiente option").each(function() 
            {
                if ($(this).text().startsWith(nome))
                {
                    ambienteId = $(this).val();
                }
            });

            return ambienteId;
        }

        $("#btn_atribuir_automaticamente").click(function() {
            alert("Que pena, vc perdeu.");
        });

        //Progração do evento "change" dos select de cursos
        $('#filtroCurso').on('change', function() {
            aulas = [];

            $(".loader-demo-box").css("visibility", "visible");

            //Limpar a tabela de horários inteira
            $("#tabela-horarios").empty();

            //Limpar card de aulas pendentes
            $('#aulasContainer').empty();

            atualizarContadorPendentes();

            $('#filtroTurma').find('option').remove().end().append('<option value="0">-</option>');
            $('#filtroTurma option[value="0"]').prop('selected', true);

            //Buscar turmas do curso selecionado.
            $.get('<?php echo base_url('sys/turma/getTurmasByCurso/'); ?>' + $('#filtroCurso').val(), function(data) {
                    $.each(data, function(idx, obj) {
                        $('#filtroTurma').append('<option value="' + obj.id + '">' + obj.sigla + '</option>');
                    });
                }, 'json')
                .done(function() {
                    $(".loader-demo-box").css("visibility", "hidden");
                });
        });

        //Progração do evento "change" dos select de turmas
        $('#filtroTurma').on('change', function() {
            aulas = [];

            $(".loader-demo-box").css("visibility", "visible");

            $("#btn_atribuir_automaticamente").prop('disabled', true);

            //Limpar a tabela de horários inteira
            $("#tabela-horarios").empty();

            atualizarContadorPendentes();

            if ($('#filtroTurma').val() != 0) {
                var quantasAulas = 0;

                //Buscar aulas da turma selecionada.
                $.get('<?php echo base_url('sys/aulas/getAulasFromTurma/'); ?>' + $('#filtroTurma').val(), function(data) {
                        //Limpar todas as aulas pendentes.
                        $('#aulasContainer').empty();

                        //Verifica se a aula atual já está na lista, para a questão de mais de um professor.
                        $.each(data, function(idx, obj) {
                            var found = false;

                            //Vetor dentro do obj para casos de aulas com mais de um professor
                            obj.professores = [];

                            //Verifica se a aula atual já está na lista, para a questão de mais de um professor.
                            $("#aulasContainer").children().each(function() {
                                //Verifica o numero da aula através do id do card.
                                var aula = $(this).attr('id').split('_')[1];

                                if (aula == obj.id) {
                                    found = true; //encontrado
                                    //Adiciona o professor na aula já existente (visual do card)
                                    $('#professor_aula_' + obj.id).append(' &nbsp; ' +
                                        '<i class="mdi mdi-account-tie fs-6 text-muted me-1"></i>' +
                                        '<small class="text-secondary">' + obj.professor /*.split(" ")[0]*/ + '</small>'
                                    );

                                    //Adiciona o professor na aula já existente (atributo data-professor)
                                    $('#aula_' + obj.id).data('professor', $('#aula_' + obj.id).data('professor') + ',' + obj.professor /*.split(" ")[0]*/ );

                                    //Coloca o professor adicional no vetor da aula já existente
                                    let objetoAlterar = getAulaById(obj.id);
                                    objetoAlterar.professores.push(obj.professor /*.split(" ")[0]*/ );
                                }
                            });

                            var regime = $('#filtroCurso option:selected').data('regime');

                            //Se não encontrou a aula atual, adiciona na lista.
                            if (!found) {
                                var cardAula = '' +
                                    '<div id="aula_' + obj.id + '" draggable="true" data-aula-id="' + obj.id + '" data-disciplina="' + obj.disciplina + '" data-professor="' + obj.professor /*.split(" ")[0]*/ + '" data-aulas-total="' + (obj.ch / ((regime == 2) ? 20 : 40)) + '" data-aulas-pendentes="' + (obj.ch / ((regime == 2) ? 20 : 40)) + '" class="card border-1 shadow-sm mx-4 my-1 bg-gradient" style="cursor: pointer;">' +
                                    '<div class="card-body p-0 d-flex flex-column justify-content-center align-items-center text-center">' +
                                    '<h6 class="text-primary">' +
                                    '<i class="mdi mdi-book-outline me-1"></i> ' + obj.disciplina +
                                    '</h6>' +
                                    '<div class="d-flex align-items-center mb-0 py-0" id="professor_aula_' + obj.id + '">' +
                                    '<i class="mdi mdi-account-tie fs-6 text-muted me-1"></i>' +
                                    '<small class="text-secondary">' + obj.professor /*.split(" ")[0]*/ + '</small>' +
                                    '</div>' +
                                    '<div class="d-flex align-items-center">' +
                                    '<i class="mdi mdi-door fs-6 text-muted me-1"></i>' +
                                    '<small class="text-secondary"><span class="aulas-pendentes">' + (obj.ch / ((regime == 2) ? 20 : 40)) + '</span> aula(s)</small>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                $('#aulasContainer').append(cardAula);

                                //Coloca o professor no vetor da aula
                                obj.professores.push(obj.professor /*.split(" ")[0]*/ );

                                //adiciona a aula carregada no vetor de aulas
                                aulas.push(obj);

                                //faz o somatório de aulas da turma
                                quantasAulas += (obj.ch / ((regime == 2) ? 20 : 40));
                            }
                        });
                    }, 'json')
                    .done(function() {
                        $("#aulasCounter").html(quantasAulas);
                        $("#btn_atribuir_automaticamente").prop('disabled', false);
                        configurarDragAndDrop();
                        $(".loader-demo-box").css("visibility", "hidden");

                        //Buscar horários da turma selecionada para montar a tabela de horários.
                        $.get('<?php echo base_url('sys/tempoAula/getTemposFromTurma/'); ?>' + $('#filtroTurma').val(), function(data) {
                            var dias = [];

                            horarios = []; //Limpa o vetor de horários

                            var temManha = false;
                            var temTarde = false;
                            var temNoite = false;

                            $.each(data['tempos'], function(idx, obj) {
                                //Montar o array com os dias do horário da turma
                                if (dias.includes(obj.dia_semana) == false) {
                                    dias.push(obj.dia_semana);
                                }

                                //Preencher o vetor de horários com todos os horarios lidos no getTemposFromTurma
                                let horario = {
                                    id: obj.id,
                                    dia_semana: obj.dia_semana,
                                    hora_inicio: obj.hora_inicio,
                                    minuto_inicio: obj.minuto_inicio,
                                    hora_fim: obj.hora_fim,
                                    minuto_fim: obj.minuto_fim
                                };
                                horarios.push(horario);

                                //Verifica se tem horário de manhã, tarde ou noite
                                if (obj.hora_inicio < 12)
                                    temManha = true;
                                if (obj.hora_inicio >= 12 && obj.hora_inicio < 18)
                                    temTarde = true;
                                if (obj.hora_inicio >= 18)
                                    temNoite = true;
                            });

                            var htmlDaTableHead = '' +
                                '<tr>' +
                                '<th class="col-1">Horário</th>';

                            //Iterar pelos dias existentes no horário
                            $.each(dias, function(idx, obj) {
                                htmlDaTableHead += '<th class="col-1">' + nome_dia[obj] + '</th>';
                            });

                            htmlDaTableHead += '' +
                                '</tr>';

                            //Insere os horários na tabela se tiver aula pela manhã
                            if (temManha) {
                                var htmlDaTabela = '' +
                                    '<thead>' +
                                    '<tr>' +
                                    '<th colspan="' + (dias.length + 1) + '" class="text-center bg-primary text-white">MANHÃ</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    htmlDaTableHead;

                                $('#tabela-horarios').append(htmlDaTabela);

                                $('#tabela-horarios').append('<tbody id="tabela-horarios-manha">');

                                //Vetor para guardar os horarios já adicionados na tabela
                                var horariosJaAdicionados = [];

                                $.each(horarios, function(idx, obj) {
                                    //Verificar se já tem o horário na lista
                                    var jaTemHorario = false;

                                    $.each(horariosJaAdicionados, function(idx2, obj2) {
                                        if (obj2.hora_inicio == obj.hora_inicio && obj2.minuto_inicio == obj.minuto_inicio && obj2.hora_fim == obj.hora_fim && obj2.minuto_fim == obj.minuto_fim) {
                                            jaTemHorario = true;
                                        }
                                    });

                                    if (!jaTemHorario) {
                                        if (obj.hora_inicio < 13) {
                                            var linhaDeHorarios = '' +
                                                '<tr>' +
                                                '<td class="coluna-fixa">' + obj.hora_inicio + ':' + obj.minuto_inicio + '-' + obj.hora_fim + ':' + obj.minuto_fim + '</td>';
                                            for (var i = 0; i < dias.length; i++) {
                                                linhaDeHorarios += '<td class="horario-vazio" id="horario_' +
                                                    getIdByDiaHoraMinuto(horarios, dias[i], obj.hora_inicio, obj.minuto_inicio, obj.hora_fim, obj.minuto_fim) +
                                                    '"></td>';
                                            }
                                            linhaDeHorarios += '' +
                                                '</tr>'

                                            $('#tabela-horarios-manha').append(linhaDeHorarios);

                                            let gravaHorario = {
                                                hora_inicio: obj.hora_inicio,
                                                minuto_inicio: obj.minuto_inicio,
                                                hora_fim: obj.hora_fim,
                                                minuto_fim: obj.minuto_fim
                                            };
                                            horariosJaAdicionados.push(gravaHorario);

                                        } //if hora < 13
                                    }
                                });

                                $('#tabela-horarios').append('</tbody>');
                            }

                            //Insere os horários na tabela se tiver aula pela tarde
                            if (temTarde) {
                                var htmlDaTabela = '' +
                                    '<thead>' +
                                    '<tr>' +
                                    '<th colspan="' + (dias.length + 1) + '" class="text-center bg-primary text-white">TARDE</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    htmlDaTableHead;

                                $('#tabela-horarios').append(htmlDaTabela);

                                $('#tabela-horarios').append('<tbody id="tabela-horarios-tarde">');

                                //Vetor para guardar os horarios já adicionados na tabela
                                var horariosJaAdicionados = [];

                                $.each(horarios, function(idx, obj) {
                                    //Verificar se já tem o horário na lista
                                    var jaTemHorario = false;

                                    $.each(horariosJaAdicionados, function(idx2, obj2) {
                                        if (obj2.hora_inicio == obj.hora_inicio && obj2.minuto_inicio == obj.minuto_inicio && obj2.hora_fim == obj.hora_fim && obj2.minuto_fim == obj.minuto_fim) {
                                            jaTemHorario = true;
                                        }
                                    });

                                    if (!jaTemHorario) {
                                        if (obj.hora_inicio >= 13 && obj.hora_inicio < 18) {
                                            var linhaDeHorarios = '' +
                                                '<tr>' +
                                                '<td class="coluna-fixa">' + obj.hora_inicio + ':' + obj.minuto_inicio + '-' + obj.hora_fim + ':' + obj.minuto_fim + '</td>';
                                            for (var i = 0; i < dias.length; i++) {
                                                linhaDeHorarios += '<td class="horario-vazio" id="horario_' +
                                                    getIdByDiaHoraMinuto(horarios, dias[i], obj.hora_inicio, obj.minuto_inicio, obj.hora_fim, obj.minuto_fim) +
                                                    '"></td>';
                                            }
                                            linhaDeHorarios += '' +
                                                '</tr>'

                                            $('#tabela-horarios-tarde').append(linhaDeHorarios);

                                            let gravaHorario = {
                                                hora_inicio: obj.hora_inicio,
                                                minuto_inicio: obj.minuto_inicio,
                                                hora_fim: obj.hora_fim,
                                                minuto_fim: obj.minuto_fim
                                            };
                                            horariosJaAdicionados.push(gravaHorario);

                                        }
                                    }
                                });

                                $('#tabela-horarios').append('</tbody>');

                            } //if tem tarde

                            //Insere os horários na tabela se tiver aula pela tarde
                            if (temNoite) {
                                var htmlDaTabela = '' +
                                    '<thead>' +
                                    '<tr>' +
                                    '<th colspan="' + (dias.length + 1) + '" class="text-center bg-primary text-white">NOITE</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    htmlDaTableHead;

                                $('#tabela-horarios').append(htmlDaTabela);

                                $('#tabela-horarios').append('<tbody id="tabela-horarios-noite">');

                                //Vetor para guardar os horarios já adicionados na tabela
                                var horariosJaAdicionados = [];

                                $.each(horarios, function(idx, obj) {
                                    //Verificar se já tem o horário na lista
                                    var jaTemHorario = false;

                                    $.each(horariosJaAdicionados, function(idx2, obj2) {
                                        if (obj2.hora_inicio == obj.hora_inicio && obj2.minuto_inicio == obj.minuto_inicio && obj2.hora_fim == obj.hora_fim && obj2.minuto_fim == obj.minuto_fim) {
                                            jaTemHorario = true;
                                        }
                                    });

                                    if (!jaTemHorario) {
                                        if (obj.hora_inicio >= 18) {
                                            var linhaDeHorarios = '' +
                                                '<tr>' +
                                                '<td class="coluna-fixa">' + obj.hora_inicio + ':' + obj.minuto_inicio + '-' + obj.hora_fim + ':' + obj.minuto_fim + '</td>';
                                            for (var i = 0; i < dias.length; i++) {
                                                linhaDeHorarios += '<td class="horario-vazio" id="horario_' +
                                                    getIdByDiaHoraMinuto(horarios, dias[i], obj.hora_inicio, obj.minuto_inicio, obj.hora_fim, obj.minuto_fim) +
                                                    '"></td>';
                                            }
                                            linhaDeHorarios += '' +
                                                '</tr>'

                                            $('#tabela-horarios-noite').append(linhaDeHorarios);

                                            let gravaHorario = {
                                                hora_inicio: obj.hora_inicio,
                                                minuto_inicio: obj.minuto_inicio,
                                                hora_fim: obj.hora_fim,
                                                minuto_fim: obj.minuto_fim
                                            };
                                            horariosJaAdicionados.push(gravaHorario);

                                        } //if hora > 18
                                    }
                                });

                                $('#tabela-horarios').append('</tbody>');

                            } // if tem noite

                            // Configura eventos após criar a tabela
                            configurarDragAndDrop();

                            $(".horario-vazio").click(function() {
                                horarioSelecionado = $(this);
                                carregarDisciplinasPendentes($(this).attr('id'));
                                modalAtribuirDisciplina.show();
                            });

                            var counter = 0;

                            $.each(data['aulas'], function(idx, obj) {
                                counter++;

                                setTimeout(function() {
                                    const aulaSelecionadaId = obj.aula_id;
                                    const aula = getAulaById(obj.aula_id);

                                    aula.destaque = obj.destaque || 0;

                                    const ambienteSelecionadoId = obj.ambiente_id;

                                    var ambientesSelecionadosNome = [];

                                    obj.ambiente.forEach(function(item) {
                                        ambientesSelecionadosNome.push(getAmbienteNome(item));
                                    });

                                    horarioSelecionado = $(`#horario_${obj.tempo_de_aula_id}`);
                                    cardAula = $(`#aula_${obj.aula_id}`);

                                    var conflitoStyle = "text-primary";
                                    var conflitoIcon = "fa-mortar-board";

                                    if (obj.tresturnos > 0) {
                                        conflitoStyle = "text-danger";
                                        conflitoIcon = "fa-warning";
                                    } else if (obj.restricao > 0) {
                                        conflitoStyle = "text-danger";
                                        conflitoIcon = "fa-warning";
                                    } else if (obj.choque > 0) {
                                        conflitoStyle = "text-warning";
                                        conflitoIcon = "fa-warning";
                                    } else if (obj.intervalo != 0) {
                                        conflitoStyle = "text-info";
                                        conflitoIcon = "fa-warning";
                                    }

                                    var btnFixar = "text-primary";

                                    if (obj.fixa == 1)
                                        btnFixar = "text-warning";

                                    var btnBypass = "text-primary";

                                    if (obj.bypass == 1)
                                        btnBypass = "text-warning";

                                    // Preenche o horário selecionado
                                    horarioSelecionado.html(`
                                    <div class="card border-1 shadow-sm bg-gradient min-height-card" style="cursor: pointer; height: 100%;">
                                        <div class="card-body p-1 d-flex flex-column justify-content-center align-items-center text-center">

                                            <h6 class="text-wrap mb-0 fs-6 ${conflitoStyle}" style="font-size: 0.75rem !important; margin-right: 15px">
                                                <i class="fa ${conflitoIcon} me-1"></i>
                                                ${aula.disciplina}
                                            </h6>

                                            <div class="d-flex align-items-center mb-0 py-0" style="margin-right: 15px">
                                                <i class="mdi mdi-account-tie fs-6 text-muted me-1"></i>
                                                <small class="text-wrap text-secondary" style="font-size: 0.65rem !important;">${aula.professores.join(", ")}</small>
                                            </div>

                                            <div class="d-flex align-items-center" style="margin-right: 15px">
                                                <i class="mdi mdi-door fs-6 text-muted me-1"></i>
                                                <small class="text-wrap text-secondary" style="font-size: 0.65rem !important;">${ambientesSelecionadosNome.join("<br />")}</small>
                                            </div>

                                            <div style="width: 100%; text-align: right; top: 0; position: absolute">
                                                <i class="mdi mdi-close-box fs-6 text-danger me-1" id="btnRemover_horario_${obj.id}"></i><br />
                                                <i class="mdi mdi-lock fs-6 ${btnFixar} me-1" id="btnFixar_horario_${obj.id}"></i><br />
                                                <i class="mdi mdi-account-multiple fs-6 ${btnBypass} me-1" id="btnBypass_horario_${obj.id}"></i><br />
                                                <i class="mdi ${(obj.aula_destaque == 1 || obj.destaque == 1) ? 'mdi-star text-warning' : 'mdi-star-outline text-primary'} fs-6 me-1" id="btnDestacar_horario_${obj.id}"></i>
                                            </div>

                                        </div>
                                    </div>
                                `);

                                    $("#btnFixar_horario_" + obj.id).off().click(function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        if (obj.fixa == 1)
                                            fixarAulaHorario(0, obj.id, obj.tempo_de_aula_id); //desfixar
                                        else
                                            fixarAulaHorario(1, obj.id, obj.tempo_de_aula_id); //fixar
                                    });

                                    $("#btnBypass_horario_" + obj.id).off().click(function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        if (obj.bypass == 1)
                                            bypassarAulaHorario(0, obj.id, obj.tempo_de_aula_id); //desbypass
                                        else
                                            bypassarAulaHorario(1, obj.id, obj.tempo_de_aula_id); //bypass
                                    });

                                    $("#btnDestacar_horario_" + obj.id).off().click(function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();

                                        const isDestaque = $(this).hasClass("mdi-star");
                                        const tipo = isDestaque ? 0 : 1;

                                        $.ajax({
                                            url: '<?php echo base_url('sys/tabela-horarios/destacarAula'); ?>',
                                            type: 'POST',
                                            dataType: 'json',
                                            data: {
                                                aula_horario_id: obj.id,
                                                tipo: tipo
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    if (tipo === 1) {
                                                        $(`#btnDestacar_horario_${obj.id}`)
                                                            .removeClass("mdi-star-outline text-primary")
                                                            .addClass("mdi-star text-warning");
                                                    } else {
                                                        $(`#btnDestacar_horario_${obj.id}`)
                                                            .removeClass("mdi-star text-warning")
                                                            .addClass("mdi-star-outline text-primary");
                                                    }
                                                    $.toast({
                                                        heading: 'Sucesso',
                                                        text: response.message || 'Operação realizada com sucesso',
                                                        showHideTransition: 'slide',
                                                        icon: 'success',
                                                        loaderBg: '#f96868',
                                                        position: 'top-center'
                                                    });
                                                } else {
                                                    // Verifica se é uma mensagem especial de toast
                                                    if (response.message && response.message.startsWith('toast:')) {
                                                        const parts = response.message.split(':');
                                                        const type = parts[1];
                                                        const text = parts[2].split('|')[0];
                                                        const duration = parts[2].split('|')[1] || 3000;

                                                        $.toast({
                                                            heading: 'Aviso',
                                                            text: text,
                                                            showHideTransition: 'slide',
                                                            icon: type,
                                                            loaderBg: '#f2a654',
                                                            position: 'top-center',
                                                            hideAfter: duration
                                                        });
                                                    } else {
                                                        $.toast({
                                                            heading: 'Erro',
                                                            text: response.message || 'Não foi possível alterar o destaque',
                                                            showHideTransition: 'slide',
                                                            icon: 'error',
                                                            loaderBg: '#f96868',
                                                            position: 'top-center'
                                                        });
                                                    }
                                                }
                                            },
                                            error: function() {
                                                $.toast({
                                                    heading: 'Erro',
                                                    text: 'Falha na comunicação com o servidor',
                                                    showHideTransition: 'slide',
                                                    icon: 'error',
                                                    loaderBg: '#f96868',
                                                    position: 'top-center'
                                                });
                                            }
                                        });
                                    });

                                    $("#btnRemover_horario_" + obj.id).off().click(function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();

                                        if ($(`#horario_${obj.tempo_de_aula_id}`).data('fixa') == 1) {
                                            alert("Aula fixada, não pode ser removida");
                                            return;
                                        }

                                        // Requisição para remover a disciplina ao horário no backend
                                        $.post('<?php echo base_url('sys/tabela-horarios/removerAula'); ?>', {
                                                aula_id: obj.aula_id,
                                                tempo_de_aula_id: obj.tempo_de_aula_id
                                            },
                                            function(data) {
                                                if (data == "1") {
                                                    moverDisciplinaParaPendentes($(`#horario_${obj.tempo_de_aula_id}`));

                                                    // Limpa o horário
                                                    $(`#horario_${obj.tempo_de_aula_id}`).html('')
                                                        .removeClass('horario-preenchido')
                                                        .addClass('horario-vazio')
                                                        .removeData(['disciplina', 'professor', 'ambiente', 'aula-id', 'aulas-total', 'aulas-pendentes'])
                                                        .off('click')
                                                        .click(function() {
                                                            horarioSelecionado = $(this);
                                                            carregarDisciplinasPendentes($(this).attr('id'));
                                                            modalAtribuirDisciplina.show();
                                                        });

                                                    configurarDragAndDrop();

                                                    // Mostra feedback de sucesso
                                                    $.toast({
                                                        heading: 'Sucesso',
                                                        text: 'A disciplina foi removida do horário.',
                                                        showHideTransition: 'slide',
                                                        icon: 'success',
                                                        loaderBg: '#f96868',
                                                        position: 'top-center'
                                                    });
                                                } else {
                                                    // Mostra feedback de erro
                                                    $.toast({
                                                        heading: 'Erro',
                                                        text: 'Ocorreu um erro ao remover a aula do horário.',
                                                        showHideTransition: 'slide',
                                                        icon: 'error',
                                                        loaderBg: '#f96868',
                                                        position: 'top-center'
                                                    });
                                                }
                                            });
                                    });

                                    // Adiciona os dados ao horário
                                    horarioSelecionado
                                        .data('disciplina', aula.disciplina)
                                        .data('professor', aula.professores.join(", "))
                                        .data('ambiente', ambienteSelecionadoId)
                                        .data('ambienteNome', ambientesSelecionadosNome)
                                        .data('aula-id', obj.aula_id)
                                        .data('aulas-total', cardAula.data('aulas-total'))
                                        .data('aulas-pendentes', cardAula.data('aulas-pendentes'))
                                        .data('conflito', obj.choque)
                                        .data('conflitoAmbiente', obj.choqueAmbiente)
                                        .data('conflitoProfessor', obj.choqueProfessor)
                                        .data('restricao', obj.restricao)
                                        .data('tresturnos', obj.tresturnos)
                                        .data('intervalo', obj.intervalo)
                                        .data('aula_horario_id', obj.id)
                                        .data('fixa', obj.fixa)
                                        .data('destacada', (obj.destaque == 1 || obj.aula_destaque == 1) ? 1 : 0)
                                        .removeClass('horario-vazio')
                                        .addClass('horario-preenchido')
                                        .off()
                                        .click(function() {
                                            mostrarModalConfirmacaoRemocao(this);
                                        });

                                    // Atualiza a quantidade de aulas pendentes no card
                                    const aulasPendentes = cardAula.data('aulas-pendentes') - 1;
                                    cardAula.data('aulas-pendentes', aulasPendentes);
                                    cardAula.find('.aulas-pendentes').text(aulasPendentes);

                                    // Se zerou, remove o card
                                    if (aulasPendentes <= 0) {
                                        cardAula.remove();
                                    }

                                    atualizarContadorPendentes();

                                }, 50 * counter); // Atraso de 50ms para cada iteração
                            });

                            // Configura eventos após preencher a tabela
                            configurarDragAndDrop();

                        }, 'json');


                    });
            } else // nenhuma turma selecionada
            {
                //Limpar a tabela de horários inteira
                $("#tabela-horarios").empty();

                //Limpar card de aulas pendentes
                $('#aulasContainer').empty();

                //Esconder o div do loader
                $(".loader-demo-box").css("visibility", "hidden");
            }
        });

        $('.js-example-basic-single').select2({
            placeholder: "Selecione uma opção:",
            width: '100%'
        });

        $("body").addClass("sidebar-icon-only");
    });
</script>