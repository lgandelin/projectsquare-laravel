<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Decorators\ReplyMessageDecorator;
use Webaccess\ProjectSquare\Exceptions\Messages\MessageReplyNotAuthorizedException;
use Webaccess\ProjectSquare\Requests\Messages\CreateConversationRequest;
use Webaccess\ProjectSquare\Requests\Messages\CreateMessageRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class MessageController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $request->session()->put('messages_interface', 'messages');

        return view('projectsquare::messages.index', [
            'conversations' => app()->make('ConversationManager')->getConversationsPaginatedList($this->getUser()->id, env('CONVERSATIONS_PER_PAGE', 10), Input::get('filter_project')),
            'projects' => app()->make('ProjectManager')->getUserProjects($this->getUser()->id),
            'filters' => [
                'project' => Input::get('filter_project'),
            ],
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

            return response()->json([
                'message' => (new ReplyMessageDecorator())->decorate($response),
            ], 200);
        } catch (MessageReplyNotAuthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function view($conversationID)
    {
        return view('projectsquare::messages.view', [
            'conversation' => app()->make('ConversationManager')->getConversationModel($conversationID),
            'back_link' => ($request->session()->get('messages_interface') === 'project') ? route('project_messages', ['uuid' => $this->getCurrentProject()->id]) : route('conversations_index')
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
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
        ], 200);
    }
}
