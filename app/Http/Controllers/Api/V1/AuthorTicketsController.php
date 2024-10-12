<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorTicketsController extends ApiController
{
    public function index($author_id, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filter($filters)->paginate());
    }

    public function store($author_id,StoreTicketRequest $request)
    {
        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $author_id,
        ];

        return new TicketResource(Ticket::create($model));
    }

    public function replace(ReplaceTicketRequest $request,$author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if($ticket->user_id == $author_id) {
                $model = [
                    'title' => $request->input('data.attributes.title'),
                    'description' => $request->input('data.attributes.description'),
                    'status' => $request->input('data.attributes.status'),
                    'user_id' => $request->input('data.relationship.author.data.id'),
                ];
                $ticket->update($model);
                return new TicketResource($ticket);
            }
        } catch(ModelNotFoundException $exception) {
            return $this->error('Ticket not been found', 404);
        }
    }

    public function destroy($author_id,$ticket_id)
    {
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            if($ticket->user_id == $author_id) {
                $ticket->delete();
                return $this->ok('Ticket deleted successfully');
            }
            return $this->error('Ticket not been found', 404);
        } catch(ModelNotFoundException $exceptoion) {
            return $this->error('Ticket not been found', 404);
        }
    }
}
