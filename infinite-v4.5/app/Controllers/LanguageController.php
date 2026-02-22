<?php

namespace App\Controllers;

use App\Models\LanguageModel;

class LanguageController extends BaseAdminController
{
    protected $languageModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        checkPermission('settings');
        $this->languageModel = new LanguageModel();
    }

    /**
     * Languages
     */
    public function languageSettings()
    {
        $data["title"] = trans("language_settings");
        $data["languages"] = $this->languageModel->getLanguages();
        

        echo view('admin/includes/_header', $data);
        echo view('admin/language/language_settings', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Set Language Post
     */
    public function setDefaultLanguagePost()
    {
        if ($this->languageModel->setDefaultLanguage()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Add Language Post
     */
    public function addLanguagePost()
    {
        $val = \Config\Services::validation();
        $val->setRule('name', trans("language_name"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $langId = $this->languageModel->addLanguage();
            if (!empty($langId)) {
                $this->languageModel->addLanguageSettings($langId);
                $this->languageModel->addLanguagePages($langId);
                setSuccessMessage("msg_item_added");
            } else {
                setErrorMessage("msg_error");
            }
        }
        redirectToBackURL();
    }

    /**
     * Edit Language
     */
    public function editLanguage($id)
    {
        $data['title'] = trans("update_language");
        //get language
        $data['language'] = $this->languageModel->getLanguage($id);
        
        if (empty($data['language'])) {
            redirectToBackURL();
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/language/update_language', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Language Post
     */
    public function editLanguagePost()
    {
        $val = \Config\Services::validation();
        $val->setRule('name', trans("language_name"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $id = inputPost('id');
            if ($this->languageModel->editLanguage($id)) {
                setSuccessMessage("msg_updated");
                return redirect()->to(adminUrl('language-settings'));
            } else {
                setErrorMessage("msg_error");
            }
        }
        redirectToBackURL();
    }

    /**
     * Delete Language Post
     */
    public function deleteLanguagePost()
    {
        $id = inputPost('id');
        $language = $this->languageModel->getLanguage($id);
        if ($language->id == 1) {
            setErrorMessage("msg_language_delete");
            exit();
        }
        if ($this->languageModel->deleteLanguage($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Translations
     */
    public function translations($id)
    {
        $data['title'] = trans('edit_translations');
        $data['show'] = clrNum(inputGet('show'));
        if ($data['show'] < 15) {
            $data['show'] = 15;
        }
        
        $data['language'] = $this->languageModel->getLanguage($id);
        if (empty($data['language'])) {
            redirectToBackURL();
        }

        $numRows = $this->languageModel->getTranslationCount($data['language']->id);
        $data['pager'] = paginate($data['show'], $numRows);
        $data['translations'] = $this->languageModel->getTranslationsPaginated($data['language']->id, $data['show'], $data['pager']->offset);

        echo view('admin/includes/_header', $data);
        echo view('admin/language/translations', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Translations Post
     */
    public function editTranslationsPost()
    {
        $langId = inputPost("lang_id");
        $ids = \Config\Services::request()->getPost();
        foreach ($ids as $key => $value) {
            if ($key != "lang_id") {
                $this->languageModel->editTranslations($langId, $key, $value);
            }
        }
        setSuccessMessage("msg_updated");
        redirectToBackURL();
    }

    /**
     * Import Language
     */
    public function importLanguagePost()
    {
        if ($this->languageModel->importLanguage()) {
            setSuccessMessage("the_operation_completed");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Export Language
     */
    public function exportLanguagePost()
    {
        if (!is_writable(FCPATH . 'uploads/temp')) {
            setErrorMessage('"uploads/temp" folder is not writable!', false);
            redirectToBackURL();
        }
        $files = glob(FCPATH . 'uploads/temp/*.json');
        if (!empty($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
        $arrayLang = $this->languageModel->exportLanguage();
        if (!empty($arrayLang)) {
            $filePath = FCPATH . 'uploads/temp/' . $arrayLang['language']->name . '.json';
            $json = json_encode($arrayLang);
            $file = fopen($filePath, 'w+');
            fwrite($file, $json);
            fclose($file);
            if (file_exists($filePath)) {
                return \Config\Services::response()->download($filePath, null);
            }
        }
        redirectToBackURL();
    }
}
