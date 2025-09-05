<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\CursosModel;
use App\Models\VersoesModel;
use App\Models\AmbientesModel;
use App\Models\AulaHorarioModel;
use App\Models\AulaHorarioAmbienteModel;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Cache\CacheInterface;


class AulaHorarioController extends BaseController
{
  public function verificarConflitosRotina() {

    $cache = cache(); /** @var CacheInterface $cache */

    $versaoId = (new \App\Models\VersoesModel())->getVersaoByUser(auth()->id());

    $cacheKey = "rotina_conflitos{$versaoId}";
    if ($conflitos = $cache->get($cacheKey)) {
        return $this->response->setJSON($conflitos);
    }

    $model = new \App\Models\AulaHorarioModel();
    $amb  = $model->countConflitosAmbiente($versaoId);
    $prof = $model->countConflitosProfessor($versaoId);
    $restricao = $model->countRestricaoDocente($versaoId);
    $turnos = $model->countTresTurnos($versaoId);

    $conflitos = [
      'CONFLITO-AMBIENTE' => $amb,
      'CONFLITO-PROFESSOR' => $prof,
      'RESTRIÇÃO-DOCENTE' => $restricao,
      'CONFLITO-TURNOS' => $turnos,
    ];

    $cache->save($cacheKey, $conflitos, 60);

    return $this->response->setJSON($conflitos);
  }

  public function destacarConflitosAmbiente()
  {
      $data = $this->request->getPost();
      $idTempoDeAula = $data['tempo_de_aula_id'];

      $aulaHorarioModel = new AulaHorarioModel();
      $conflitos = $aulaHorarioModel->destacandoConflitoAmbiente($idTempoDeAula);

      if (!empty($conflitos)) {
          return $this->response->setJSON(
              $conflitos
          );
      } else {
          return $this->response->setJSON([
              'mensagem' => 'Sem Conflitos!',
          ]);
      }
      return $this->response->setJSON(['status' => 'ok']);
  }  
}
