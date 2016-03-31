<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Exceptions\Messages\MessageReplyNotAuthorizedException;
use Webaccess\ProjectSquare\Requests\Messages\CreateConversationRequest;
use Webaccess\ProjectSquare\Requests\Messages\CreateMessageRequest;
use Webaccess\ProjectSquare\Requests\Messages\ReadMessageRequest;

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
        try {
            $response = app()->make('CreateMessageInteractor')->execute(new CreateMessageRequest([
                'content' => Input::get('message'),
                'conversationID' => Input::get('conversation_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $data = [
                'id' => $response->message->id,
                'datetime' => $response->createdAt->format('d/m/Y H:i:s'),
                'username' => $response->user->firstName . ' ' . $response->user->lastName,
                'message' => $response->message->content,
                'count' => $response->count,
            ];

            return response()->json(['message' => $data], 200);
        } catch (MessageReplyNotAuthorizedException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function view($conversationID)
    {
        $conversation = app()->make('ConversationManager')->getConversationModel($conversationID);

        foreach ($conversation->messages as $message) {
            $message->read = true;

            app()->make('ReadMessageInteractor')->execute(new ReadMessageRequest([
                'messageID' => $message->id,
                'requesterUserID' => $this->getUser()->id,
            ]));
        }

        return view('projectsquare::messages.view', [
            'conversation' => $conversation,
        ]);
    }

    public function addConversation()
    {
        try {
            app()->make('CreateConversationInteractor')->execute(new CreateConversationRequest([
                'title' => Input::get('title'),
                'message' => Input::get('message'),
                'projectID' => ($this->getCurrentProject()) ? $this->getCurrentProject()->id : null,
                'requesterUserID' => $this->getUser()->id,
            ]));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true], 200);
    }
}
