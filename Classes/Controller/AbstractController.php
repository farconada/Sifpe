<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;

class AbstractController extends \F3\FLOW3\MVC\Controller\ActionController {
    protected function mapRequestArgumentsToControllerArguments() {
        try {
            parent::mapRequestArgumentsToControllerArguments();
        } catch (\Exception $ex) {
            $this->forward('error',NULL,NULL,array('msg' => $ex->getMessage()));
        }
    }

    /**
     * @param string $msg
     */
    public function errorAction($msg='') {
        $this->view->assign('msg',$msg);
    }
}