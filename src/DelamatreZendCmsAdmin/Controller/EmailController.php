<?php

namespace DelamatreZendCmsAdmin\Controller;


use Admin\Mvc\Controller\RelatedContent;
use DelamatreZendCms\Entity\Email;
use DelamatreZendCmsAdmin\Form\EmailTestForm;
use DelamatreZendCmsAdmin\Mvc\Controller\AbstractEntityAdminController;
use Zend\View\Model\ViewModel;

class EmailController extends AbstractEntityAdminController
{

    use RelatedContent;

    public $routeName = 'email';
    public $entityName = 'DelamatreZendCms\Entity\Email';
    public $formName = 'DelamatreZendCmsAdmin\Form\EmailForm';

    public function previewAction(){

        $this->requireAuthentication();

        $id = $this->params()->fromQuery('id');

        /* @var Email $entity */
        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            $entity = new $this->entityName();
        }else{

            /** @var \DelamatreZendCms\Entity\Blog $blog */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }
        }

        echo $entity->generateHtml();
        exit();

    }

    public function sendTestAction(){

        $this->requireAuthentication();

        $id = $this->params()->fromQuery('id');

        if(empty($id)){
            throw new \Exception("No ID specified");
        }

        /* @var Email $entity */
        $entity = $this->getEntityManager()->find($this->entityName,$id);

        if(empty($entity)){
            throw new \Exception('No '.$this->entityName.' found for id '.$id);
        }

        $form = new EmailTestForm();

        if($this->getRequest()->isPost()){

            $post = $this->params()->fromPost();
            $form->setData($post);
            if($form->isValid()){


                $message = $entity->generateMessage($this->getConfig()['myapp']['baseurl']);

                $mail = $this->createMail();
                $mail->setBody($message);
                $mail->setSubject($entity->getSubject());
                $mail->setTo($post['email'],$post['name']);

                $this->getSmtp()->send($mail);


                //send email to test recipients
                //$email = $this->getSmtp();

            }

        }


        $view = new ViewModel();
        $view->entity = $entity;
        $view->form = $form;
        return $view;

    }


}
