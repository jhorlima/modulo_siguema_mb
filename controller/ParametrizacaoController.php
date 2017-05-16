<?php

namespace SigUema\controller;

use MocaBonita\controller\MbController;
use MocaBonita\tools\MbException;
use MocaBonita\tools\MbRequest;
use MocaBonita\tools\MbResponse;
use MocaBonita\view\MbView;
use Parametrizacao\model\Parametrizacao;

/**
 * Class ParametrizacaoController
 * @package SigUema\controller
 */
class ParametrizacaoController extends MbController
{
    /**
     * Example of index action
     *
     * If the return is null, then it will call MbView from this controller and then render it (Common request)
     * If the return is null, then it will print a message saying "No valid content has been submitted!" (Ajax Request)
     *
     * If the return is string, then it will print the string (Common request)
     * If the return is string, then it will add the string in the key "content" on the data (Ajax Request)
     *
     * If the return is MbView, then it will render it (Common request)
     * If the return is MbView, then it will print a message saying "No valid content has been submitted!" (Ajax Request)
     *
     * If the return is mixed, then it will call a var_dump of the returned value (Common request)
     * If the return is mixed, then it will print a message saying "No valid content has been submitted!" (Ajax Request)
     *
     * @param MbRequest $mbRequest
     * @param MbResponse $mbResponse
     *
     * @return MbView
     */
    public function indexAction(MbRequest $mbRequest MbResponse $mbResponse)
    {
        return Parametrizacao::getMbView([
            'sigws_url',
            'sigws_name',
            'sigws_token',
            'sigws_timeout',
            'hash_query_lister',
            'hash_query',
        ], true);
    }

    /**
     * @param MbRequest $mbRequest
     * @param MbResponse $mbResponse
     * @throws MbException
     */
    public function salvarAction(MbRequest $mbRequest, MbResponse $mbResponse)
    {
        $mbView = $this->indexAction($mbRequest, $mbResponse);

        try {
            Parametrizacao::salvarParametro($mbRequest->input());
            $mbResponse->redirect($mbRequest->fullUrlWithNewAction('index'));
        } catch (MbException $e) {
            $e->setExceptionData($mbView);
            throw $e;
        } catch (\Exception $e) {
            throw new MbException($e->getMessage(), $e->getCode(), $mbView);
        }
    }

}