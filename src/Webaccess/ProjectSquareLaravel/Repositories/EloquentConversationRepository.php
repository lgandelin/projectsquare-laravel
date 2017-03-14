<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquare\Entities\Conversation as ConversationEntity;
use Webaccess\ProjectSquare\Entities\Project as ProjectEntity;
use Webaccess\ProjectSquare\Repositories\ConversationRepository;
use Webaccess\ProjectSquareLaravel\Models\Conversation;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\User;

class EloquentConversationRepository implements ConversationRepository
{
    public $projectRepository;

    public function __construct()
    {
        $this->projectRepository = new EloquentProjectRepository();
    }

    public function getConversation($conversationID)
    {
        if ($conversationModel = $this->getConversationModel($conversationID)) {
            $conversation = new ConversationEntity();
            $conversation->id = $conversationModel->id;
            $conversation->title = $conversationModel->title;
            $conversation->projectID = $conversationModel->project_id;

            return $conversation;
        }

        return false;
    }

    public function getConversationModel($conversationID)
    {
        return Conversation::with('messages')->with('messages.user')->find($conversationID);
    }

    public function getConversationsPaginatedList($userID, $projectID = null, $limit = null)
    {
        //Ressource projects
        $projects = User::find($userID)->projects()->where('status_id', '=', ProjectEntity::IN_PROGRESS);
        $projectIDs = $projects->pluck('id')->toArray();

        //Client project
        $user = User::find($userID);
        if (isset($user->client_id)) {
            $project = Project::where('client_id', '=', $user->client_id)->where('status_id', '=', ProjectEntity::IN_PROGRESS)->orderBy('created_at', 'DESC')->first();
            $projectIDs[]= $project->id;
        }

        $conversations = Conversation::with('messages')->with('messages.user')->with('project')->with('project.client')->orderBy('created_at', 'DESC');

        if ($projectID != null) {
            $conversations->where('project_id', '=', $projectID);
        } else {
            $conversations->whereIn('project_id', $projectIDs);
        }

        return $conversations->paginate($limit);
    }

    public function getConversationsByProject($projectID, $limit = null)
    {
        $conversations = Conversation::with('messages')->with('messages.user')->with('project')->with('project.client')->orderBy('created_at', 'DESC')->where('project_id', '=', $projectID);
        if ($limit) {
            $conversations->limit($limit);
        }

        return $conversations->get();
    }

    public function persistConversation(ConversationEntity $conversation)
    {
        if (!isset($conversation->id)) {
            $conversationModel = new Conversation();
            $conversationID = Uuid::uuid4()->toString();
            $conversationModel->id = $conversationID;
            $conversation->id = $conversationID;
        } else {
            Conversation::find($conversation->id);
        }
        $conversationModel->title = $conversation->title;
        if ($project = $this->projectRepository->getProjectModel($conversation->projectID)) {
            $conversationModel->project()->associate($project);
        }
        $conversationModel->save();

        $conversation->id = $conversationModel->id;

        return $conversation;
    }

    public function deleteConversationByProjectID($projectID)
    {
        Conversation::where('project_id', '=', $projectID)->delete();
    }
}
