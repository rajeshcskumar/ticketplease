<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    public function show($ticket_id)
    {
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            if($this->include('author')) {
                return new TicketResource($ticket->load('user'));
            }
            return new TicketResource($ticket);
        } catch(ModelNotFoundException $exceptoion) {
            return $this->error('Ticket not been found', 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            $user = User::findOrFail($request->input('data.relationship.author.data.id'));
        } catch(ModelNotFoundException $exception) {
            return $this->ok('User Not Found', [
                'error' => 'The Provided user id does not exists'
            ]);
        }
        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch(ModelNotFoundException $exception) {
            return $this->error('Ticket not been found', 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch(ModelNotFoundException $exception) {
            return $this->error('Ticket not been found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();
            return $this->ok('Ticket deleted successfully');
        } catch(ModelNotFoundException $exceptoion) {
            return $this->error('Ticket not been found', 404);
        }
    }
}
