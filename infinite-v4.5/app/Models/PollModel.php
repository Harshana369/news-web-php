<?php namespace App\Models;

use CodeIgniter\Model;

class PollModel extends BaseModel
{
    protected $builder;
    protected $builderOptions;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('polls');
        $this->builderOptions = $this->db->table('poll_options');
    }

    //input values
    public function inputValues()
    {
        return [
            'lang_id' => inputPost('lang_id'),
            'question' => inputPost('question'),
            'status' => inputPost('status')
        ];
    }

    //add poll
    public function addPoll()
    {
        $data = $this->inputValues();
        $data["created_at"] = date('Y-m-d H:i:s');
        if ($this->builder->insert($data)) {
            $pollId = $this->db->insertID();

            $inputOptions = inputPost('option');
            if (!empty($inputOptions)) {
                foreach ($inputOptions as $key => $value) {
                    $this->builderOptions->insert(["poll_id" => $pollId, "option_text" => $value, "votes" => 0]);
                }
            }
            return true;
        }
        return false;
    }

    //update poll
    public function editPoll($id)
    {
        $poll = $this->getPoll($id);
        if (!empty($poll)) {
            //set values
            $data = $this->inputValues();

            $options = $this->getPollOptions($id);
            if (!empty($options)) {
                foreach ($options as $option) {
                    if (null !== inputPost('option_' . $option->id)) {
                        $newText = inputPost('option_' . $option->id);
                        $this->builderOptions->where('id', $option->id)->update(["option_text" => $newText]);
                    } else {
                        $this->builderOptions->where('id', $option->id)->delete();
                    }
                }
            }

            $newOptions = inputPost('option');
            if (!empty($newOptions)) {
                foreach ($newOptions as $key => $value) {
                    $this->builderOptions->insert(["poll_id" => $id, "option_text" => $value, "votes" => 0]);
                }
            }

            return $this->builder->where('id', clrNum($id))->update($data);
        }
        return false;
    }

    //get polls
    public function getPolls($langId = null, $pollId = null)
    {
        if ($langId !== null) {
            $this->builder->where('lang_id', clrNum($langId));
        }
        if ($pollId !== null) {
            $this->builder->where('polls.id', clrNum($pollId));
        }

        $results = $this->builder->select('polls.*, poll_options.id AS option_id, poll_options.option_text, poll_options.votes')
            ->join('poll_options', 'polls.id = poll_options.poll_id', 'left')->orderBy('polls.id', 'DESC')->get()->getResult();

        if (empty($results)) {
            return [];
        }

        $polls = [];

        foreach ($results as $row) {
            if (!is_object($row) || !property_exists($row, 'id')) {
                continue;
            }

            $pollId = $row->id;

            if (!isset($polls[$pollId])) {
                $poll = new \stdClass();
                $poll->id = $row->id ?? null;
                $poll->question = $row->question ?? '';
                $poll->lang_id = $row->lang_id ?? 0;
                $poll->status = $row->status ?? 0;
                $poll->created_at = $row->created_at ?? '';
                $poll->options = [];
                $poll->total_votes = 0;
                $polls[$pollId] = $poll;
            }

            if (!is_null($row->option_id)) {
                $option = new \stdClass();
                $option->id = $row->option_id;
                $option->option_text = $row->option_text ?? '';
                $option->votes = is_numeric($row->votes) ? (int)$row->votes : 0;

                $polls[$pollId]->total_votes += $option->votes;
                $polls[$pollId]->options[] = $option;
            }
        }

        foreach ($polls as $poll) {
            foreach ($poll->options as $option) {
                $option->percentage = $poll->total_votes > 0 ? round(($option->votes / $poll->total_votes) * 100, 2) : 0;
            }
        }

        return array_values($polls);
    }

    //get poll
    public function getPoll($id)
    {
        $polls = $this->getPolls(null, $id);
        if (!empty($polls) && !empty($polls[0])) {
            return $polls[0];
        }
    }

    //get poll options
    public function getPollOptions($pollId)
    {
        return $this->builderOptions->where('poll_id', clrNum($pollId))->get()->getResult();
    }

    //get poll option
    public function getPollOption($optionId)
    {
        return $this->builderOptions->where('id', clrNum($optionId))->get()->getRow();
    }

    //add vote
    public function addVote($pollId, $optionId)
    {
        $poll = $this->getPoll($pollId);
        if (!empty($poll)) {
            if (!empty(helperGetCookie('poll_vote_' . $poll->id))) {
                return "voted";
            } else {
                $option = $this->getPollOption($optionId);
                if (!empty($option)) {
                    $votes = $option->votes;
                    if ($this->builderOptions->where('id', $option->id)->update(["votes" => $votes + 1])) {
                        helperSetCookie('poll_vote_' . $poll->id, 1);
                        return "success";
                    }
                }
            }
        }
    }

    //delete poll
    public function deletePoll($id)
    {
        $poll = $this->getPoll($id);
        if (!empty($poll)) {
            $this->builderOptions->where('poll_id', $poll->id)->delete();
            return $this->builder->where('id', $poll->id)->delete();
        }
        return false;
    }
}
