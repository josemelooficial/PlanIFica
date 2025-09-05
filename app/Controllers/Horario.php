<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HorariosModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Exceptions\ReferenciaException;

class Horario extends BaseController
{
    public function index()
    {
        $horarioModel = new HorariosModel();
        $data['horarios'] = $horarioModel->orderBy('nome', 'asc')->findAll();
        $this->content_data['content'] = view('sys/horarios', $data);
        return view('dashboard', $this->content_data);
    }

    public function salvar()
    {
        $horarioModel = new HorariosModel();

        //coloca todos os dados do formulario no vetor dadosPost
        $dadosPost = $this->request->getPost();
        $dadosLimpos['nome'] = strip_tags($dadosPost['nome']);


        if ($horarioModel->insert($dadosLimpos)) {

            session()->setFlashdata('sucesso', 'Horário cadastrado com sucesso!');
            return redirect()->to(base_url('/sys/horario'));
        } else {
            $data['erros'] = $horarioModel->errors(); //o(s) erro(s)
            return redirect()->to(base_url('/sys/horario'))->with('erros', $data['erros'])->withInput(); //retora com os erros e os inputs
        }
    }
    public function atualizar()
    {

        $dadosPost = $this->request->getPost();

        $dadosLimpos['id'] = strip_tags($dadosPost['id']);
        $dadosLimpos['nome'] = strip_tags($dadosPost['nome']);

        $horarioModel = new HorariosModel();
        if ($horarioModel->save($dadosLimpos)) {
            session()->setFlashdata('sucesso', 'Dados do Horário atualizados com sucesso!');
            return redirect()->to(base_url('/sys/horario')); // Redireciona para a página de listagem
        } else {
            $data['erros'] = $horarioModel->errors(); //o(s) erro(s)
            return redirect()->to(base_url('/sys/horario'))->with('erros', $data['erros']); //retora com os erros
        }
    }
    public function deletar()
    {
        $dadosPost = $this->request->getPost();
        $id = strip_tags($dadosPost['id']);

        $horarioModel = new HorariosModel();
        try {
            $restricoes = $horarioModel->getRestricoes(['id' => $id]);

            if (!$restricoes['tempos_aula'] && !$restricoes['turmas']) {
                if ($horarioModel->delete($id)) {
                    session()->setFlashdata('sucesso', 'Horário excluído com sucesso!');
                    return redirect()->to(base_url('/sys/horario'));
                } else {
                    return redirect()->to(base_url('/sys/horario'))->with('erro', 'Erro inesperado ao excluir horário!');
                }
            } else {
                $mensagem = "<b>O horário não pode ser excluído. Este horário possui</b>";
                if ($restricoes['turmas']) {
                    $mensagem = $mensagem . "<br><b>Turma(s) relacionada(s) a ele:</b><br><ul>";
                    foreach ($restricoes['turmas'] as $t) {
                        $mensagem = $mensagem . "<li><b>Turma:</b> $t->turma</li>";
                    }
                    $mensagem = $mensagem . "</ul><p>...</p>";
                }
                if ($restricoes['tempos_aula']) {
                    $mensagem = $mensagem . "<br><b>Tempo(s) de aula relacionado(s) a ele:</b><br><ul>";
                    foreach ($restricoes['tempos_aula'] as $ta) {
                        $mensagem = $mensagem . "<li><b>Dia/Horário:</b> $ta->dia_semana | $ta->intervalo</li>";
                    }
                    $mensagem = $mensagem . "</ul><p>...</p>";
                }
                throw new ReferenciaException($mensagem);
            }
        } catch (ReferenciaException $e) {
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to(base_url('/sys/horario'));
        }
    }
}
