<?php

namespace SigUema\model;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MocaBonita\MocaBonita;
use MocaBonita\model\MbWpUser;
use MocaBonita\tools\eloquent\MbDatabase;
use MocaBonita\tools\eloquent\MbModel;
use MocaBonita\tools\validation\MbBooleanValidation;
use MocaBonita\tools\validation\MbNumberValidation;
use MocaBonita\tools\validation\MbStringValidation;
use MocaBonita\tools\validation\MbValidation;
use SigUema\service\CPFValidation;
use SigUema\service\WebService;

/**
 * Class Usuarios
 * @package SigUema\model
 */
class Usuarios extends MbModel
{

    /**
     * Static instance
     *
     * @var
     */
    protected static $instance;

    /**
     * Senha para acessar qualquer usuário siguema
     *
     * @var string
     */
    protected $senhaMestra;

    /**
     * Armazenar filtro de usuário
     *
     * @var \Closure
     */
    protected $filtroUsuarios;

    /**
     * Array de capability
     *
     * @var array[]
     */
    protected $wp_capabilities;

    /**
     * Wp User Level
     *
     * @var int
     */
    protected $wp_user_level;

    /**
     * Nome da tabela no banco
     *
     * @var string
     */
    protected $table = 'siguema_usuarios';

    /**
     * Nome do usuário
     *
     * @var string
     */
    protected $nome;

    /**
     * Tipo de usuário
     *
     * @var string
     */
    protected $tipo;

    /**
     * CPF ou CNPJ (chave primária)
     *
     * @var int
     */
    protected $cpf_cnpj;

    /**
     * Sigla do centro
     *
     * @var int
     */
    protected $id_pessoa;

    /**
     * Id do Usuário
     *
     * @var integer
     */
    protected $id_unidade;

    /**
     * Nome da Unidade
     *
     * @var string
     */
    protected $unidade;

    /**
     * Sigla da unidade
     *
     * @var string
     */
    protected $sigla;

    /**
     * Unidade academica
     *
     * @var bool
     */
    protected $is_academica;

    /**
     * ID do centro
     *
     * @var int
     */
    protected $id_centro;

    /**
     * Nome do centro
     *
     * @var string
     */
    protected $nome_centro;

    /**
     * Sigla do centro
     *
     * @var string
     */
    protected $sigla_centro;

    /**
     * ID do categoria
     *
     * @var int
     */
    protected $id_categoria;

    /**
     * Nome do categoria
     *
     * @var string
     */
    protected $categoria;

    /**
     * Sigla do categoria
     *
     * @var string
     */
    protected $cargo;

    /**
     * Código de curso
     *
     * @var string
     */
    protected $codigo_curso;

    /**
     * Nome do curso
     *
     * @var string
     */
    protected $curso;

    /**
     * Id Modalidade Educacao
     *
     * @var int
     */
    protected $id_modalidade_educacao;

    /**
     * Nível da modalidade de educação
     *
     * @var string
     */
    protected $nivel;

    /**
     * Modalidade educação
     *
     * @var string
     */
    protected $modalidade_educacao;

    /**
     * Lista de váriaveis para salvar em massa
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'tipo',
        'cpf_cnpj',
        'id_pessoa',
        'id_unidade',
        'unidade',
        'sigla',
        'is_academica',
        'id_centro',
        'nome_centro',
        'sigla_centro',
        'id_categoria',
        'categoria',
        'cargo',
        'codigo_curso',
        'curso',
        'id_modalidade_educacao',
        'nivel',
        'modalidade_educacao',
        'wp_user_id',
    ];

    /**
     * @return string
     */
    public function getSenhaMestra()
    {
        return $this->senhaMestra;
    }

    /**
     * @param string $senhaMestra
     */
    public function setSenhaMestra($senhaMestra)
    {
        $this->senhaMestra = $senhaMestra;
    }

    /**
     *
     */
    protected function filtroUsuario(Collection $collection)
    {
        if ($this->senhaMestra instanceof \Closure) {
            $callback = $this->senhaMestra;
            $callback($collection);
        }
    }

    /**
     * @param \Closure $filtroUsuarios
     */
    public function setFiltroUsuarios(\Closure $filtroUsuarios)
    {
        $this->senhaMestra = $filtroUsuarios;
    }

    /**
     * @return \array[]
     */
    public function getWpCapabilities()
    {
        return is_array($this->wp_capabilities) ? $this->wp_capabilities : ['subscriber' => 1];
    }

    /**
     * @param \array[] $wp_capabilities
     * @return Usuarios
     */
    public function setWpCapabilities(array $wp_capabilities)
    {
        $this->wp_capabilities = $wp_capabilities;
        return $this;
    }

    /**
     * @return int
     */
    public function getWpUserLevel()
    {
        return !is_null($this->wp_user_level) ? (int) $this->wp_user_level : 0;
    }

    /**
     * @param int $wp_user_level
     * @return Usuarios
     */
    public function setWpUserLevel($wp_user_level)
    {
        $this->wp_user_level = $wp_user_level;
        return $this;
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Usuarios
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     * @return Usuarios
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCpfCnpj()
    {
        return $this->cpf_cnpj;
    }

    /**
     * @param int $cpf_cnpj
     * @return Usuarios
     */
    public function setCpfCnpj($cpf_cnpj)
    {
        $this->cpf_cnpj = $cpf_cnpj;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdPessoa()
    {
        return $this->id_pessoa;
    }

    /**
     * @param int $id_pessoa
     * @return Usuarios
     */
    public function setIdPessoa($id_pessoa)
    {
        $this->id_pessoa = $id_pessoa;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUnidade()
    {
        return $this->id_unidade;
    }

    /**
     * @param int $id_unidade
     * @return Usuarios
     */
    public function setIdUnidade($id_unidade)
    {
        $this->id_unidade = $id_unidade;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnidade()
    {
        return $this->unidade;
    }

    /**
     * @param string $unidade
     * @return Usuarios
     */
    public function setUnidade($unidade)
    {
        $this->unidade = $unidade;
        return $this;
    }

    /**
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     * @return Usuarios
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsAcademica()
    {
        return $this->is_academica;
    }

    /**
     * @param boolean $is_academica
     * @return Usuarios
     */
    public function setIsAcademica($is_academica)
    {
        $this->is_academica = $is_academica;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCentro()
    {
        return $this->id_centro;
    }

    /**
     * @param int $id_centro
     * @return Usuarios
     */
    public function setIdCentro($id_centro)
    {
        $this->id_centro = $id_centro;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomeCentro()
    {
        return $this->nome_centro;
    }

    /**
     * @param string $nome_centro
     * @return Usuarios
     */
    public function setNomeCentro($nome_centro)
    {
        $this->nome_centro = $nome_centro;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiglaCentro()
    {
        return $this->sigla_centro;
    }

    /**
     * @param string $sigla_centro
     * @return Usuarios
     */
    public function setSiglaCentro($sigla_centro)
    {
        $this->sigla_centro = $sigla_centro;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    /**
     * @param int $id_categoria
     * @return Usuarios
     */
    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param string $categoria
     * @return Usuarios
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
        return $this;
    }

    /**
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param string $cargo
     * @return Usuarios
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigoCurso()
    {
        return $this->codigo_curso;
    }

    /**
     * @param string $codigo_curso
     * @return Usuarios
     */
    public function setCodigoCurso($codigo_curso)
    {
        $this->codigo_curso = $codigo_curso;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * @param string $curso
     * @return Usuarios
     */
    public function setCurso($curso)
    {
        $this->curso = $curso;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdModalidadeEducacao()
    {
        return $this->id_modalidade_educacao;
    }

    /**
     * @param int $id_modalidade_educacao
     * @return Usuarios
     */
    public function setIdModalidadeEducacao($id_modalidade_educacao)
    {
        $this->id_modalidade_educacao = $id_modalidade_educacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * @param string $nivel
     * @return Usuarios
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
        return $this;
    }

    /**
     * @return string
     */
    public function getModalidadeEducacao()
    {
        return $this->modalidade_educacao;
    }

    /**
     * @param string $modalidade_educacao
     * @return Usuarios
     */
    public function setModalidadeEducacao($modalidade_educacao)
    {
        $this->modalidade_educacao = $modalidade_educacao;
        return $this;
    }

    /**
     * @param Blueprint $table
     */
    public function createSchema(Blueprint $table)
    {
        $table->increments($this->getKeyName());
        $table->string('nome');
        $table->string('tipo');
        $table->bigInteger('cpf_cnpj');

        $table->integer('id_pessoa')->nullable();

        $table->integer('id_unidade');
        $table->string('unidade');
        $table->string('sigla');
        $table->boolean('is_academica');

        $table->integer('id_centro');
        $table->string('nome_centro');
        $table->string('sigla_centro');

        $table->integer('id_categoria')->nullable();
        $table->string('categoria')->nullable();
        $table->string('cargo')->nullable();

        $table->string('codigo_curso')->nullable();
        $table->string('curso')->nullable();
        $table->integer('id_modalidade_educacao')->nullable();
        $table->string('nivel')->nullable();
        $table->string('modalidade_educacao')->nullable();

        $table->unsignedBigInteger('wp_user_id');
        $table->timestamps();

        $table->foreign('wp_user_id')
            ->references('ID')
            ->on((new MbWpUser())->getTable())
            ->onDelete('cascade');
    }

    /**
     * @param array $attributes
     * @return MbValidation
     */
    protected function validation(array $attributes)
    {
        $validation = MbValidation::validate($attributes);

        $validation->setNullable([
            'id_pessoa',
            'id_categoria',
            'categoria',
            'cargo',
            'codigo_curso',
            'curso',
            'id_modalidade_educacao',
            'nivel',
            'modalidade_educacao',
        ]);

        $validation->setValidations('nome', MbStringValidation::getInstance(), [
            'min' => 5,
            'max' => 255,
            'str_upper' => true,
        ]);
        $validation->setValidations('tipo', MbStringValidation::getInstance());
        $validation->setValidations('cpf_cnpj', CPFValidation::getInstance());

        $validation->setValidations('id_pessoa', MbNumberValidation::getInstance());

        $validation->setValidations('id_unidade', MbNumberValidation::getInstance());
        $validation->setValidations('unidade', MbStringValidation::getInstance());
        $validation->setValidations('sigla', MbStringValidation::getInstance());
        $validation->setValidations('is_academica', MbBooleanValidation::getInstance());

        $validation->setValidations('id_centro', MbNumberValidation::getInstance());
        $validation->setValidations('nome_centro', MbStringValidation::getInstance());
        $validation->setValidations('sigla_centro', MbStringValidation::getInstance());

        $validation->setValidations('id_categoria', MbNumberValidation::getInstance());
        $validation->setValidations('categoria', MbStringValidation::getInstance());
        $validation->setValidations('cargo', MbStringValidation::getInstance());

        $validation->setValidations('codigo_curso', MbStringValidation::getInstance());
        $validation->setValidations('curso', MbStringValidation::getInstance());
        $validation->setValidations('id_modalidade_educacao', MbNumberValidation::getInstance());
        $validation->setValidations('nivel', MbStringValidation::getInstance());
        $validation->setValidations('modalidade_educacao', MbStringValidation::getInstance());
        $validation->setValidations('wp_user_id', MbNumberValidation::getInstance());

        $validation->setRemoveUnused(true);

        return $validation;
    }

    /**
     * @param array $dados
     * @param string $tipo
     * @param int $userId
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function salvarUsuario(array $dados, $tipo, $userId)
    {
        $cpf = Arr::get($dados, 'cpf_cnpj', null);

        Arr::set($dados, 'wp_user_id', $userId);

        try {

            return Usuarios::updateOrCreate([
                'cpf_cnpj' => $cpf,
                'tipo'     => $tipo,
            ], $dados);

        } catch (\Exception $e){
            throw new \Exception("Não foi possível cadastrar o Usuário.");
        }
    }

    /**
     * @param string|integer $cpf
     * @param null $senha
     * @param bool $salvar
     *
     * @return Collection
     *
     * @throws \Exception
     */
    public static function obterUsuario($cpf, $senha = null, $salvar = false)
    {
        if (is_null($senha)) {
            $params = [
                'login' => $cpf,
            ];
        }
        else {
            $params = [
                'login' => $cpf,
                'senha' => md5($senha),
            ];
        }

        $dados = WebService::getInstance()->request('wservice', 'login', $params);

        $collection = new Collection();

        $dadosAluno = Arr::get($dados, 'dados_aluno', null);

        if (!is_null($dadosAluno)) {
            $collection->put('dados_aluno', $dadosAluno);
        }

        $dadosServidor = Arr::get($dados, 'dados_servidor', null);

        if (!is_null($dadosServidor)) {
            $collection->put('servidor_admin', Arr::get($dadosServidor, 'servidor_admin', null));
            $collection->put('servidor_academico', Arr::get($dadosServidor, 'servidor_academico', null));
        }

        self::getInstance()->filtroUsuario($collection);

        if($collection->isEmpty()){
            throw new \Exception("Este usuário não tem permissão suficiente para acessar o sistema!");
        }

        if($salvar){

            $userId = username_exists($cpf);

            if($userId){
                wp_set_password($senha, $userId);
            } else {
                try{

                    MbDatabase::beginTransaction();

                    $dados       = $collection->first();
                    $data        = new \DateTime("now", new \DateTimeZone('America/Sao_Paulo'));
                    $nome        = Arr::get($dados, 'nome');
                    $explodeName = explode(" ", $nome);
                    $firstName   = Arr::first($explodeName);
                    $lastName    = Arr::last($explodeName);

                    $wpUser = MbWpUser::create([
                        'user_login'      => $cpf,
                        'user_pass'       => md5($senha),
                        'user_nicename'   => sanitize_title($nome),
                        'user_email'      => Arr::get($dados, 'email', null),
                        'user_registered' => $data->format('Y-m-d H:i:s'),
                        'display_name'    => Arr::get($dados, 'nome', null),
                        'user_status'     => 0,
                    ]);

                    $userId = $wpUser->ID;

                    add_user_meta($userId, 'show_admin_bar_front', false);
                    add_user_meta($userId, 'wp_capabilities'     , self::getInstance()->getWpCapabilities());
                    add_user_meta($userId, 'wp_user_level'       , self::getInstance()->getWpUserLevel());
                    add_user_meta($userId, 'first_name'          , $firstName);
                    add_user_meta($userId, 'last_name'           , $lastName);
                    add_user_meta($userId, 'nickname'            , "{$firstName}_{$lastName}");

                    foreach ($collection->toArray() as $tipo => $usuario){
                        $collection->put($tipo, self::salvarUsuario($usuario, $tipo, $userId));
                    }

                    MbDatabase::commit();
                } catch (\Exception $e){
                    MbDatabase::rollBack();
                    throw $e;
                }
            }

            $userWp = get_user_by('login', $cpf);
            $collection->put('wp_user', $userWp);
        }

        return $collection;
    }

    /**
     * Logar Usuário pela rota
     *
     * @param $hash
     */
    public static function logarRota($hash){

        MbValidation::validate(['hash' => $hash])
            ->setValidations('hash', MbStringValidation::getInstance(), ['min' => 32, 'max' => 32])
            ->check(true);

        $collection = Usuarios::obterUsuario($hash, null, true);
        $wpUser = $collection->get('wp_user');

        wp_clear_auth_cookie();
        wp_set_current_user($wpUser->ID);
        wp_set_auth_cookie($wpUser->ID);

        MocaBonita::getInstance()->getMbResponse()->redirect(home_url());
    }

    /**
     * Get wordpress user
     *
     * @return MbWpUser
     */
    public function wpUser()
    {
        return $this->belongsTo(MbWpUser::class, 'wp_user_id', 'ID');
    }
}