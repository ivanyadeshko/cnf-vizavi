<?php

namespace common;


use common\traits\errorsTrait;

abstract class WebController
{

    use errorsTrait;

    protected $viewsDir = 'views';

    protected $layout = 'main';

    /**
     * @var null
     */
    protected $tplDir = null;


    /**
     * @return bool
     */
    public function init() : bool
    {
        return true;
    }

    /**
     *
     */
    public function end() : void
    {

    }


    /**
     * @param string $tpl
     * @param array $data
     * @return string
     */
    public function render($tpl, array $data = []) : string
    {
        return $this->renderPartial(
            '/layouts/' . $this->layout,
            ['content' => $this->renderPartial($tpl, $data)]
        );
    }


    /**
     * @param string $tpl
     * @param array $data
     * @return string
     */
    public function renderPartial(string $tpl, array $data = []) : string
    {
        if (strpos($tpl, '/') === false) {
            $tplDir = ($this->tplDir === null)
                ? mb_strtolower(str_replace(["Controller", "\\"], ["", "/"], get_class($this)))
                : $this->tplDir;
            $tplName = $tplDir . '/' . $tpl;
        }
        else
            $tplName = $tpl;

        return $this->goRender($data, $this->getTplsDir() . DIRECTORY_SEPARATOR . $tplName . '.php');
    }


    /**
     * @param array $data
     * @param string $path
     * @return string
     */
    private function goRender(array $data, string $path) : string
    {
        extract($data);
        ob_start();

        require($path);

        $renderedContent = ob_get_contents();
        ob_end_clean();
        return $renderedContent;
    }


    /**
     * @return string
     */
    protected function getTplsDir() : string
    {
        return _DATA_DIR_ . DIRECTORY_SEPARATOR . $this->viewsDir;
    }

}