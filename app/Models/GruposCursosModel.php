<?php namespace App\Models;

use CodeIgniter\Model;

class GruposCursosModel extends Model {
  protected $table            = 'grupos_de_cursos';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = ['nome'];

  protected bool $allowEmptyInserts = false;
  protected bool $updateOnlyChanged = true;

  protected array $casts = [];
  protected array $castHandlers = [];

  // Dates
  protected $useTimestamps = false;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  // Validation
  protected $validationRules = [
    'id' => 'permit_empty|is_natural_no_zero|max_length[11]',
    'nome' => 'required|max_length[64]|is_unique[grupos_de_cursos.nome,id,{id}]',
  ];
  protected $validationMessages   = [
    "nome" => [
      "required" => "O campo nome é obrigatório",
      "max_length" => "O tamanho máximo é 64 caracteres",
      "is_unique" => "O Grupo Curso já cadastrado",
    ]
  ];
  protected $skipValidation       = false;
  protected $cleanValidationRules = true;

  // Callbacks
  protected $allowCallbacks = true;
  protected $beforeInsert   = [];
  protected $afterInsert    = [];
  protected $beforeUpdate   = [];
  protected $afterUpdate    = [];
  protected $beforeFind     = [];
  protected $afterFind      = [];
  protected $beforeDelete   = [];
  protected $afterDelete    = [];

  public function getGruposWithCursos() {
    $grupos = $this->findAll();
    $grupos = array_map(function($grupo) {
      $grupo['cursos'] = 
        $this->db->table('curso_grupo AS cg')
        ->select('cg.id, cg.curso_id, c.nome')
        ->join('cursos AS c', 'cg.curso_id = c.id')
        ->where('cg.grupo_de_cursos_id', $grupo['id'])
        ->get()->getResultArray();

      return $grupo;
    }, $grupos);

    return $grupos;
  }
}