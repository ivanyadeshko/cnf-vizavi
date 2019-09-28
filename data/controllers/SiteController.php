<?php

namespace controllers;


use common\Request;
use common\WebController;
use models\file_processing\CsvFilesUploaderModel;


class SiteController extends WebController
{

    /**
     * @return string
     */
    public function actionIndex() : string
    {
        return $this->render('files_uploading');
    }


    /**
     * @return string
     */
    public function actionCsvProcessor() : string
    {
        $CsvFilesUploaderModel = new CsvFilesUploaderModel();
        $CsvFilesUploaderModel->load(
            Request::getInstance()->files()
        );

        if (!$CsvFilesUploaderModel->validate()) {
            $this->addErrors($CsvFilesUploaderModel->getErrors());
            return $this->render('files_results', [
                'errors' => $this->getErrors()
            ]);
        }

        return $this->render('files_results', [
            'groupsComposite' => $CsvFilesUploaderModel->getGroupsComposite(),
            'productsComposite' => $CsvFilesUploaderModel->getProductsComposite(),
            'errors' => $this->getErrors()
        ]);

    }


}