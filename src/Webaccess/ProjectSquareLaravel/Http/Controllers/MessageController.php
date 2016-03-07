<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Interactors\Conversations\CreateConversationInteractor;
use Webaccess\ProjectSquare\Interactors\Messages\CreateMessageInteractor;
use Webaccess\ProjectSquare\Requests\Conversations\CreateConversationRequest;
use Webaccess\ProjectSquare\Requests\Messages\CreateMessageRequest;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentConversationRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;

class MessageController extends BaseController
{
    public function index()
    {
        return view('projectsquare::messages.index', [
            'conversations' => app()->make('ConversationManager')->getConversationsPaginatedList(),
        ]);
    }

    public function reply()
    {
        $response = (new CreateMessageInteractor(
            new EloquentMessageRepository(),
            new EloquentConversationRepository(),
            new EloquentUserRepository(),
            new EloquentProjectRepository()
        ))->execute(new CreateMessageRequest([
            'content' => Input::get('message'),
            'conversationID' => Input::get('conversation_id'),
            'requesterUserID' => $this->getUser()->id
        ]));

        $data = [
            'id' => $response->message->id,
            'datetime' => $response->createdAt->format('d/m/Y H:i:s'),
            'username' => $response->user->firstName . ' ' . $response->user->lastName,
            'message' => $response->message->content,
            'count' => $response->count
        ];

        return response()->json(['message' => $data], 200);
    }

    public function view($conversationID)
    {
        $conversation = app()->make('ConversationManager')->getConversationModel($conversationID);

        return view('projectsquare::messages.view', [
            'conversation' => $conversation,
        ]);
    }

    public function addConversation()
    {
        try {
            (new CreateConversationInteractor(
                new EloquentConversationRepository(),
                new EloquentMessageRepository(),
                new EloquentUserRepository(),
                new EloquentProjectRepository()
            ))->execute(new CreateConversationRequest([
                'title' => Input::get('title'),
                'message' => Input::get('message'),
                'projectID' => ($this->getCurrentProject()) ? $this->getCurrentProject()->id : null,
                'requesterUserID' => $this->getUser()->id
            ]));

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true], 200);
    }
}
