<?php namespace App\Models;

use CodeIgniter\Model;

class NewsletterModel extends BaseModel
{
    protected $builder;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('subscribers');
    }

    //add to subscriber
    public function addSubscriber($email)
    {
        $data = [
            'email' => $email,
            'token' => generateToken(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->builder->insert($data);
    }

    //update subscriber token
    public function updateSubscriberToken($email)
    {
        $subscriber = $this->getSubscriber($email);
        if (!empty($subscriber)) {
            if (empty($subscriber->token)) {
                $data = [
                    'token' => generateToken()
                ];
                $this->builder->where('email', $email)->update($data);
            }
        }
    }

    //update settings
    public function updateSettings()
    {
        $data = [
            'newsletter_status' => inputPost('newsletter_status'),
            'newsletter_popup' => inputPost('newsletter_popup')
        ];

        $uploadModel = new UploadModel();
        $file = $uploadModel->uploadTempFile('file');
        if (!empty($file) && !empty($file['path'])) {
            @unlink(FCPATH . $this->generalSettings->newsletter_image);
            $data['newsletter_image'] = $uploadModel->uploadNewsletterImage($file['path']);
        }
        return $this->db->table('general_settings')->where('id', 1)->update($data);
    }

    //get subscribers count
    public function getSubscribersCount()
    {
        return $this->builder->countAllResults();
    }

    //load more subscribers
    public function loadMoreSubscribers($q, $perPage, $offset)
    {
        $q = cleanStr($q);
        if (!empty($q)) {
            $this->builder->like('email', $q);
        }
        return $this->builder->orderBy('id')->limit($perPage, $offset)->get()->getResult();
    }

    //get subscribers
    public function getSubscribers()
    {
        return $this->builder->get()->getResult();
    }

    //get subscriber
    public function getSubscriber($email)
    {
        return $this->builder->where('email', cleanStr($email))->get()->getRow();
    }

    //get subscriber by id
    public function getSubscriberById($id)
    {
        return $this->builder->where('id', clrNum($id))->get()->getRow();
    }

    //delete subscribers
    public function deleteSubscriber($id)
    {
        return $this->builder->where('id', clrNum($id))->delete();
    }

    //get subscriber by token
    public function getSubscriberByToken($token)
    {
        $token = removeSpecialCharacters($token);
        return $this->builder->where('token', $token)->get()->getRow();
    }

    //get subscriber emails by ids
    public function getSubscriberEmailsByIds($ids)
    {
        $emails = array();
        $rows = $this->builder->select('email')->whereIn('id', $ids, false)->get()->getResult();
        if (!empty($rows)) {
            $emails = array_map(function ($item) {
                return $item->email;
            }, $rows);
        }
        return $emails;
    }

    //unsubscribe email
    public function unsubscribeEmail($email)
    {
        return $this->builder->where('email', $email)->delete();
    }

    //send email
    public function sendEmail()
    {
        $emailModel = new EmailModel();
        $email = inputPost('email');
        $subject = inputPost('subject');
        $body = inputPost('body');
        $submit = inputPost('submit');
        if ($submit == "subscribers") {
            $subscriber = $this->getSubscriber($email);
            if (!empty($subscriber)) {
                if ($emailModel->sendEmailNewsletter($subscriber, $subject, $body)) {
                    return true;
                }
            }
        } else {
            $data = [
                'subject' => $subject,
                'message' => $body,
                'to' => $email,
                'template_path' => "email/email_newsletter",
                'subscriber_token' => null,
            ];
            return $emailModel->sendEmail($data);
        }
        return false;
    }
}
