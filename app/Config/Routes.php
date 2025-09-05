<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\LoginController; // Corrigido aqui

/**
 * @var RouteCollection $routes
 */

// Shield Auth routes
service('auth')->routes($routes, ['except' => ['login']]);

// Definindo a rota de login para usar o seu controller personalizado
$routes->get('login', [LoginController::class, 'loginView'], ['as' => 'login']);
$routes->post('login', [LoginController::class, 'loginAction']);

$routes->get('/', 'Home::home');
$routes->get('/sys', 'Home::home');
$routes->get('/sys/home', 'Home::home');

$routes->get('/sys/em-construcao', 'Home::emConstrucao');

//cadastro de turmas
$routes->get('sys/cadastro-turmas', 'Turmas::index');

//horarios de aula
$routes->get('sys/cadastro-horarios-de-aula', 'TemposAula::cadastro');

//adicionar o filter (middleware de login no group depois)
$routes->group('sys', function ($routes) {
    $routes->group('tabela-horarios', function ($routes) {
        $routes->get('', 'TabelaHorarios::index');
        $routes->get('teste/(:num)', 'TabelaHorarios::teste/$1');
        $routes->post('atribuirAula', 'TabelaHorarios::atribuirAula');
        $routes->post('removerAula', 'TabelaHorarios::removerAula');
        $routes->get('dadosDaAula/(:num)', 'TabelaHorarios::dadosDaAula/$1');
        $routes->post('fixarAula', 'TabelaHorarios::fixarAula');
        $routes->get('verificar-todos-conflitos', 'AulaHorarioController::verificarConflitosRotina');
        $routes->post('destacarAula', 'TabelaHorarios::destacarAula');        
        $routes->post('destacar-conflitos-ambiente', 'AulaHorarioController::destacarConflitosAmbiente');
        $routes->post('bypassAula', 'TabelaHorarios::bypassAula');
    });

    $routes->group('cadastro-ambientes', function ($routes) {
        $routes->get('', 'Ambientes::index');
        $routes->post('salvar-ambiente', 'Ambientes::salvarAmbiente');
        $routes->post('deletar-ambiente', 'Ambientes::deletarAmbiente');
        $routes->post('atualizar-ambiente', 'Ambientes::atualizarAmbiente');
        $routes->post('salvar-grupo-ambientes', 'Ambientes::salvarGrupoAmbientes');
        $routes->post('deletar-grupo-ambientes', 'Ambientes::deletarGrupoAmbientes');
        $routes->post('editar-grupo-ambientes', 'Ambientes::editarGrupoAmbientes');
        $routes->post('adicionar-ambientes-grupo', 'Ambientes::adicionarAmbientesAoGrupo');
        $routes->post('remover-ambientes-grupo', 'Ambientes::removerAmbienteDoGrupo');
    });

    $routes->group('professor', function ($routes) {
        $routes->get('', 'Professor::index');
        $routes->get('listar', 'Professor::index');
        $routes->get('cadastro', 'Professor::cadastro');
        $routes->post('salvar', 'Professor::salvar');
        $routes->post('atualizar', 'Professor::atualizar');
        $routes->post('deletar', 'Professor::deletar');
        $routes->post('importar', 'Professor::importar');
        $routes->post('processarImportacao', 'Professor::processarImportacao');
        // $routes->get('preferencias/(:num)', 'Professor::preferencias/$1');
        $routes->post('salvarRestricoes', 'Professor::salvarRestricoes');
        $routes->get('restricoes/(:num)', 'Professor::buscarRestricoes/$1');
        $routes->get('(:num)', 'Professor::professorPorId/$1');
        //Rota área de trabalho
        $routes->get('horarios', 'Professor::horarios');
    });

    $routes->group('matriz', function ($routes) {
        $routes->get('', 'MatrizCurricular::index');
        $routes->get('cadastro', 'MatrizCurricular::cadastro');
        $routes->post('salvar', 'MatrizCurricular::salvar');
        $routes->post('atualizar', 'MatrizCurricular::atualizar');
        $routes->post('deletar', 'MatrizCurricular::deletar');
        $routes->post('importar', 'MatrizCurricular::importar');
        $routes->post('processarImportacao', 'MatrizCurricular::processarImportacao');
        $routes->post('importarDisciplinas', 'MatrizCurricular::importarDisciplinas');
        $routes->post('processarImportacaoDisciplinas', 'MatrizCurricular::processarImportacaoDisciplinas');
    });

    $routes->group('horario', function ($routes) {
        $routes->get('', 'Horario::index');
        $routes->get('cadastro', 'Horario::cadastro');
        $routes->post('salvar', 'Horario::salvar');
        $routes->post('atualizar', 'Horario::atualizar');
        $routes->post('deletar', 'Horario::deletar');
    });

    $routes->group('curso', function ($routes) {
        $routes->get('', 'Cursos::index');
        $routes->get('listar', 'Cursos::index');
        $routes->get('cadastro', 'Cursos::cadastro');
        $routes->post('salvar', 'Cursos::salvar');
        $routes->post('atualizar', 'Cursos::atualizar');
        $routes->post('deletar', 'Cursos::deletar');
        $routes->post('importar', 'Cursos::importar');
        $routes->post('processarImportacao', 'Cursos::processarImportacao');
        $routes->post('salvar-grupo', 'Cursos::salvarGrupo');
        $routes->post('atualizar-grupo', 'Cursos::atualizarGrupo');
        $routes->post('deletar-grupo', 'Cursos::deletarGrupo');
        $routes->post('adicionar-curso-grupo', 'Cursos::adicionarCursoAoGrupo');
        $routes->post('remover-curso-grupo', 'Cursos::removerCursoDoGrupo');
    });

    $routes->group('disciplina', function ($routes) {
        //CRUD Disciplinas
        $routes->get('', 'Disciplinas::index');
        $routes->get('listar', 'Disciplinas::index');
        $routes->get('cadastro', 'Disciplinas::cadastro');
        $routes->post('salvar', 'Disciplinas::salvar');
        $routes->post('atualizar', 'Disciplinas::atualizar');
        $routes->post('deletar', 'Disciplinas::deletar');
    });

    $routes->group('tempoAula', function ($routes) {
        $routes->get('', 'TemposAula::index');
        $routes->get('listar', 'TemposAula::index');
        $routes->get('cadastro', 'TemposAula::cadastro');
        $routes->post('salvar', 'TemposAula::salvar');
        $routes->post('atualizar', 'TemposAula::atualizar');
        $routes->post('deletar', 'TemposAula::deletar');
        $routes->get('getTemposFromTurma/(:num)', 'TemposAula::getTemposFromTurma/$1');
    });

    $routes->group('turma', function ($routes) {
        $routes->get('', 'Turmas::index');
        $routes->get('listar', 'Turmas::index');
        $routes->get('cadastro', 'Turmas::cadastro');
        $routes->post('salvar', 'Turmas::salvar');
        $routes->post('atualizar', 'Turmas::atualizar');
        $routes->post('deletar', 'Turmas::deletar');
        $routes->post('importar', 'Turmas::importar');
        $routes->post('processarImportacao', 'Turmas::processarImportacao');
        $routes->get('getTurmasByCurso/(:num)', 'Turmas::getTurmasByCurso/$1');
        $routes->get('getTurmasByCursoAndSemestre/(:num)/(:num)', 'Turmas::getTurmasByCursoAndSemestre/$1/$2');
    });

    $routes->group('aulas', function ($routes) {
        $routes->get('', 'Aulas::index');
        $routes->post('salvar', 'Aulas::salvar');
        $routes->post('deletar', 'Aulas::deletar');
        $routes->post('deletarMulti', 'Aulas::deletarMulti');
        $routes->post('atualizar', 'Aulas::atualizar');
        $routes->get('getAulasFromTurma/(:num)', 'Aulas::getAulasFromTurma/$1');
        $routes->get('getTableByAjax', 'Aulas::getTableByAjax');
    });

    $routes->group('versao', function ($routes) {
        $routes->get('', 'Versao::index');
        $routes->get('listar', 'Versao::index');
        $routes->get('cadastro', 'Versao::cadastro');
        $routes->post('salvar', 'Versao::salvar');
        $routes->post('atualizar', 'Versao::atualizar');
        $routes->post('deletar', 'Versao::deletar');
        $routes->post('ativar', 'Versao::ativar');
        $routes->post('definirVersaoVigente', 'Versao::definirVersaoVigente');
        $routes->post('duplicar', 'Versao::duplicar');
    });

    $routes->group('admin', ['filter' => 'admin'], function ($routes) {
        $routes->get('/', 'AdminController::index'); // Página inicial da admin
        $routes->post('alterar-grupo', 'AdminController::alterarGrupoUsuario'); // Atribuir
        $routes->post('atualizar-usuario', 'AdminController::atualizarUsuario');
        $routes->post('resetar-senha', 'AdminController::resetarSenha'); // Atualizar senha
        $routes->post('desativar-usuario', 'AdminController::desativarUsuario');
        $routes->post('registrar-usuario', 'AdminController::registrarUsuario');
        $routes->get('usuarios-inativos', 'AdminController::usuariosInativos');
        $routes->post('reativar-usuario', 'AdminController::reativarUsuario');
        $routes->post('excluir-permanentemente', 'AdminController::excluirPermanentemente');
    });

    $routes->group('relatorios', function ($routes) {
        $routes->get('/', 'Relatorios::index');
        $routes->post('filtrar', 'Relatorios::filtrar');
        $routes->post('getCursosByGrupo', 'Relatorios::getCursosByGrupo');
        $routes->post('getTurmasByCurso', 'Relatorios::getTurmasByCurso');
        $routes->post('getAmbientesByGrupo', 'Relatorios::getAmbientesByGrupo');
        $routes->get('relatorios/gerar', 'Relatorios::gerar');
        $routes->post('exportar', 'Relatorios::exportar');
        $routes->post('exportarXLSX','Relatorios::exportarXLSX');
    });
});
